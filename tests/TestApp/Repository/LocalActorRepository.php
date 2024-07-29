<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ActorRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;

/**
 * @extends ActorRepository<LocalActor>
 */
class LocalActorRepository extends ActorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocalActor::class);
    }

    public function findOneByUsername(string $username): ?LocalActor
    {
        return $this->findOneBy(['preferredUsername' => $username]);
    }
}
