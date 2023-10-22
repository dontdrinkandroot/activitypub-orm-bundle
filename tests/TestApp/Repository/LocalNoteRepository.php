<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\CrudServiceEntityRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalNote;
use Symfony\Component\Uid\Uuid;

/**
 * @extends CrudServiceEntityRepository<LocalNote>
 */
class LocalNoteRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocalNote::class);
    }

    public function findOneByUuid(Uuid $uuid): ?LocalNote
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }
}
