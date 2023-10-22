<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\LocalObject;

/**
 * @extends CrudServiceEntityRepository<LocalObject>
 */
class LocalObjectRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocalObject::class);
    }

    public function hasObject(Uri $uri): bool
    {
        return $this->count(['uri' => $uri]) > 0;
    }

    public function findByUri(Uri $localObjectId): ?LocalObject
    {
        return $this->findOneBy(['uri' => $localObjectId]);
    }
}
