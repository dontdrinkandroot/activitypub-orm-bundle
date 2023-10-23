<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;

/**
 * @template T of StoredActor
 * @extends StoredObjectRepository<T>
 */
class StoredActorRepository extends StoredObjectRepository
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = StoredActor::class)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @return T|null
     */
    public function findOneByUsernameAndDomain(string $username, string $domain): ?LocalActor
    {
        return $this->findOneBy(['preferredUsername' => $username, 'domain' => $domain]);
    }
}
