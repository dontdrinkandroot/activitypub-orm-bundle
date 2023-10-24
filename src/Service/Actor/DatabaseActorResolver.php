<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor as ActivityPubActor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\ObjectContent;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ObjectContentRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredActorRepository;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DatabaseActorResolver implements ActorResolverInterface
{
    public function __construct(
        private readonly ActivityPubClientInterface $activityPubClient,
        private readonly StoredActorRepository $actorRepository,
        private readonly CacheInterface $cache,
        private readonly SerializerInterface $serializer,
        private readonly ObjectContentRepository $objectContentRepository
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $actorId): ?Actor
    {
        $dbActor = $this->findOrCreate($actorId);
        if (null === $dbActor) {
            return null;
        }

        $content = Asserted::notNull(
            $this->objectContentRepository->find($dbActor->getId())?->getContent(),
            'Actor content not found'
        );
        return $this->serializer->deserialize(
            $content,
            CoreType::class,
            ActivityStreamEncoder::FORMAT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function resolveInbox(Uri $actorId): ?Uri
    {
        return $this->findOrCreate($actorId)?->inbox;
    }

    /**
     * {@inheritdoc}
     */
    public function resolvePublicKey(Uri $actorId): ?string
    {
        return $this->findOrCreate($actorId)?->publicKey;
    }

    public function findOrCreate(Uri $actorId): ?StoredActor
    {
        // TODO: refetch if expired
        $dbActor = $this->actorRepository->findOneByUri($actorId);
        if (null !== $dbActor) {
            return $dbActor;
        }

        $actor = $this->fetch($actorId);
        if (null === $actor) {
            return null;
        }
        $dbActor = new StoredActor(
            uri: $actor->getId(),
            type: $actor->getType(),
            preferredUsername: $actor->preferredUsername,
            domain: Asserted::notNull($actorId->host),
            name: $actor->name,
            summary: $actor->summary,
            inbox: $actor->inbox,
            publicKey: $actor->publicKey?->publicKeyPem
        );
        $this->actorRepository->create($dbActor);
        $this->objectContentRepository->create(
            new ObjectContent($dbActor, $this->serializer->serialize($actor, ActivityStreamEncoder::FORMAT))
        );

        return $dbActor;
    }

    private function fetch(Uri $actorId): ?ActivityPubActor
    {
        $cacheKey = $this->getCacheKey($actorId);
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($actorId) {
            $item->expiresAfter(86400);
            return Asserted::instanceOfOrNull(
                $this->activityPubClient->request('GET', $actorId),
                ActivityPubActor::class
            );
        });
    }

    private function getCacheKey(Uri $actorIri): string
    {
        return 'actor_' . md5((string)$actorIri);
    }
}
