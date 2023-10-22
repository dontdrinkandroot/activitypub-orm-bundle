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

    public function findByLocalActorAndRemoteActorId(LocalActorInterface $localActor, string $remoteActorId): ?\App\Entity\ActivityPub\Follower
    {
        return $this->findOneBy(['localActor' => $localActor, 'remoteActorId' => $remoteActorId]);
    }

    /**
     * @return Follower[]
     */
    public function findByLocalActorAndAccepted(LocalActorInterface $localActor, bool $accepted): array
    {
        return $this->findBy(['localActor' => $localActor, 'accepted' => $accepted]);
    }

    public function countByLocalActor(LocalActorInterface $localActor): int
    {
        return $this->count(['localActor' => $localActor, 'accepted' => true]);
    }
}
