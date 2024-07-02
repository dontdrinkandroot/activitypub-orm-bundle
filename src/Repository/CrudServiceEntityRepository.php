<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class CrudServiceEntityRepository extends ServiceEntityRepository
{
    /**
     * @param T $entity
     */
    public function create(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param T $entity
     */
    public function update(object $entity): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @param T $entity
     */
    public function delete(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
