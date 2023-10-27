<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectProviderInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredActorRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\DatabaseObjectPersisterInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\ObjectContentStorage;
use Dontdrinkandroot\Common\Asserted;

class DatabaseActorService implements ObjectProviderInterface, DatabaseObjectPersisterInterface
{
    public function __construct(
        private readonly StoredActorRepository $actorRepository,
        private readonly ObjectContentStorage $objectContentStorage,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function provide(Uri $uri, ?SignKey $signKey = null): Actor|false
    {
        return $this->objectContentStorage->find($uri, Actor::class) ?? false;
    }
    /**
     * {@inheritdoc}
     */
    public function persist(object $object): bool
    {
        if (!$object instanceof Actor) {
            return false;
        }
        $actor = $object;

        $dbActor = new StoredActor(
            uri: $actor->getId(),
            type: $actor->getType(),
            preferredUsername: $actor->preferredUsername,
            domain: Asserted::notNull($object->getId()->host),
            name: $actor->name,
            summary: $actor->summary,
            inbox: $actor->inbox,
            publicKey: $actor->publicKey?->publicKeyPem
        );
        $this->actorRepository->create($dbActor);
        $this->objectContentStorage->store($dbActor, $actor);

        return true;
    }
}
