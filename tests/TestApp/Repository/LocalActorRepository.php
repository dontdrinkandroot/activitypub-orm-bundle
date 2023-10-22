<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\CrudServiceEntityRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;

/**
 * @extends CrudServiceEntityRepository<LocalActor>
 */
class LocalActorRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocalActor::class);
    }

    public function findOneByUsername(string $username): ?LocalActor
    {
        return $this->findOneBy(['username' => $username]);
    }
}
