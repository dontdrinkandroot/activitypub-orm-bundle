<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;

/**
 * @template T of Actor
 * @extends ObjectRepository<T>
 */
class ActorRepository extends ObjectRepository
{
    /**
     * @param class-string<T> $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = Actor::class)
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
