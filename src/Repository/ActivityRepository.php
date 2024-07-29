<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Activity;

/**
 * @template T of Activity
 * @extends ObjectRepository<T>
 */
class ActivityRepository extends ObjectRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass = Activity::class)
    {
        parent::__construct($registry, $entityClass);
    }
}
