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
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param T $entity
     */
    public function update(object $entity): void
    {
        $this->_em->flush();
    }

    /**
     * @param T $entity
     */
    public function delete(object $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
