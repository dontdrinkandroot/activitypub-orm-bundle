<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject as DbObject;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ObjectRepository;
use Override;

class GenericDatabaseObjectPersister implements DatabaseObjectPersisterInterface
{
    public function __construct(
        private readonly ObjectRepository $storedObjectRepository,
        private readonly ObjectContentStorageInterface $objectContentStorage,
    ) {
    }

    #[Override]
    public function persist(object $object): bool
    {
        $storedObject = $this->storedObjectRepository->findOneByUri($object->getId());
        if (null !== $storedObject) {
            return true;
        }

        $storedObject = new DbObject($object->getId(), $object->getType());
        $this->storedObjectRepository->create($storedObject);

        $this->objectContentStorage->store($storedObject, $object);

        return true;
    }
}
