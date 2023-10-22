<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follower;

/**
 * @extends CrudServiceEntityRepository<Follower>
 */
class FollowerRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follower::class);
    }

    /**
     * @return Follower[]
     */
    public function findByLocalActorAndAccepted(
        LocalActorInterface $localActor,
        bool $accepted,
        int $offset = 0,
        int $limit = 50
    ): array {
        return $this->findBy(
            ['localActor' => $localActor, 'accepted' => $accepted],
            ['id' => 'ASC'],
            $limit,
            $offset
        );
    }

    public function countByLocalActor(LocalActorInterface $localActor, bool $accepted = true): int
    {
        return $this->count(['localActor' => $localActor, 'accepted' => $accepted]);
    }
}
