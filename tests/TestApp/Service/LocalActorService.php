<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\TypeClassRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorPopulator;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository\LocalActorRepository;
use Dontdrinkandroot\Common\Asserted;
use Override;

class LocalActorService implements LocalActorServiceInterface
{
    public function __construct(
        private readonly LocalActorPopulator $localActorPopulator,
        private readonly TypeClassRegistry $typeClassRegistry,
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
        private readonly LocalActorRepository $localActorRepository
    ) {
    }

    #[Override]
    public function findLocalActorByUsername(string $username): ?LocalActor
    {
        return $this->localActorRepository->findOneByUsername($username);
    }

    #[Override]
    public function findLocalActorByUri(Uri $uri): ?LocalActorInterface
    {
        $username = $this->localActorUriGenerator->matchUsername($uri);
        if (null === $username) {
            return null;
        }

        return $this->findLocalActorByUsername($username);
    }

    #[Override]
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

    public function toActivityPubActor(LocalActorInterface $localActor): Actor
    {
        Asserted::instanceOf($localActor, LocalActor::class);
        $actor = $this->localActorPopulator->populate($localActor, ActorType::from($localActor->type));
        $actor->publicKey = new PublicKey(
            id: $actor->getId()->withFragment('main-key'),
            owner: Asserted::notNull($actor->id),
            publicKeyPem: Asserted::notNull($localActor->publicKey)
        );

        return $actor;
    }

    #[Override]
    public function provide(Uri $uri, ?SignKey $signKey): Actor|false|null
    {
        $username = $this->localActorUriGenerator->matchUsername($uri);
        if (null === $username) {
            return false;
        }

        $localActor = $this->findLocalActorByUsername($username);
        if (null === $localActor) {
            return null;
        }

        return $this->toActivityPubActor($localActor);
    }
}
