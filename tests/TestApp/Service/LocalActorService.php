<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\JsonLdContext;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository\LocalActorRepository;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocalActorService implements LocalActorServiceInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TypeClassRegistry $typeClassRegistry,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
        private readonly LocalActorRepository $localActorRepository
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function findLocalActorByUsername(string $username): ?LocalActor
    {
        return $this->localActorRepository->findOneByUsername($username);
    }

    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface
    {
        $username = $this->localActorUriGenerator->matchUsername($uri);
        if (null === $username) {
            return null;
        }

        return $this->findLocalActorByUsername($username);
    }

    /**
     * {@inheritdoc}
     */
    public function getSignKey(LocalActorInterface $localActor): SignKey
    {
        Asserted::instanceOf($localActor, LocalActor::class);
        $id = $this->localActorUriGenerator->generateId($localActor);
        return new SignKey(
            id: $id->withFragment('main-key'),
            owner: $id,
            privateKeyPem: $localActor->privateKey,
            publicKeyPem: Asserted::notNull($localActor->publicKey),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toActivityPubActor(LocalActorInterface $localActor): Actor
    {
        Asserted::instanceOf($localActor, LocalActor::class);
        $actor = $this->typeClassRegistry->actorFromType(ActorType::from($localActor->type));
        $actor->jsonLdContext = new JsonLdContext(['https://w3id.org/security/v1']);
        $actor->id = $this->localActorUriGenerator->generateId($localActor->getUsername());
        $actor->inbox = $this->localActorUriGenerator->generateInbox($localActor->getUsername());
        $actor->preferredUsername = $localActor->getUsername();
        $actor->publicKey = new PublicKey(
            id: $actor->getId()->withFragment('main-key'),
            owner: $actor->id,
            publicKeyPem: Asserted::notNull($localActor->publicKey)
        );

        return $actor;
    }
}
