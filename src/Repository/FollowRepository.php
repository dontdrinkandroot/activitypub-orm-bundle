<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;

/**
 * @extends CrudServiceEntityRepository<Follow>
 */
class FollowRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follow::class);
    }

    public function findOneByLocalActorAndRemoteActor(
        LocalActorInterface $localActor,
        Actor $remoteActor,
        Direction $direction
    ): ?Follow {
        $queryBuilder = $this->createQueryBuilder('follow')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.remoteActor = :remoteActor')
            ->andWhere('follow.direction = :direction')
            ->setParameter('localActor', $localActor)
            ->setParameter('remoteActor', $remoteActor)
            ->setParameter('direction', $direction);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Follow[]
     */
    public function findAllByLocalActor(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        $queryBuilder = $this->createQueryBuilder('follow')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.direction = :direction')
            ->andWhere('follow.state = :state')
            ->setParameter('localActor', $localActor)
            ->setParameter('direction', $direction)
            ->setParameter('state', $followState)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    public function countByLocalActor(LocalActorInterface $localActor, Direction $direction,  FollowState $followState = FollowState::ACCEPTED,): int
    {
        $queryBuilder = $this->createQueryBuilder('follow')
            ->select('COUNT(follow)')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.direction = :direction')
            ->andWhere('follow.state = :state')
            ->setParameter('localActor', $localActor)
            ->setParameter('state', $followState)
            ->setParameter('direction', $direction);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
