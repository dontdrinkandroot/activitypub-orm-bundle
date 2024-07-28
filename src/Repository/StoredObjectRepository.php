<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject;

/**
 * @template T of Object
 * @extends CrudServiceEntityRepository<T>
 */
class StoredObjectRepository extends CrudServiceEntityRepository
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = CoreObject::class)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @return T|null
     */
    public function findOneByUri(Uri $uri): ?CoreObject
    {
        return $this->findOneBy(['uri' => $uri]);
    }
}
