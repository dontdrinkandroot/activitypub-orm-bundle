<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor as ActivityPubActor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor as DbActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\RawType;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ActorRepository;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class DatabaseActorResolver implements ActorResolverInterface
{
    public function __construct(
        private readonly ActivityPubClientInterface $activityPubClient,
        private readonly ActorRepository $actorRepository,
        private readonly CacheInterface $cache,
        private readonly SerializerInterface $serializer
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

        return $this->serializer->deserialize(
            $dbActor->raw->content,
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

    private function findOrCreate(Uri $actorId): ?DbActor
    {
        // TODO: refetch if expired
        $dbActor = $this->actorRepository->findByUri($actorId);
        if (null !== $dbActor) {
            return $dbActor;
        }

        $actor = $this->fetch($actorId);
        if (null === $actor) {
            return null;
        }
        $dbActor = new DbActor(
            uri: $actor->getId(),
            type: ActorType::from($actor->getType()),
            raw: new RawType(
                uri: $actor->getId(),
                type: $actor->getType(),
                content: $this->serializer->serialize($actor, ActivityStreamEncoder::FORMAT)
            ),
            preferredUsername: $actor->preferredUsername,
            name: $actor->name,
            summary: $actor->summary,
            inbox: $actor->inbox,
            publicKey: $actor->publicKey?->publicKeyPem
        );
        $this->actorRepository->create($dbActor);

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
