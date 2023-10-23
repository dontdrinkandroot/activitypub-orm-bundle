<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;

/**
 * @template T of AbstractFollow
 */
abstract class AbstractFollowRepository extends CrudServiceEntityRepository
{
    /**
     * @return T|null
     */
    public function findOneByLocalActorAndRemotActor(
        LocalActorInterface $localActor,
        StoredActor $remoteActor
    ): ?AbstractFollow {
        $queryBuilder = $this->createQueryBuilder('follow')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.remoteActor = :remoteActor')
            ->setParameter('localActor', $localActor)
            ->setParameter('remoteActor', $remoteActor);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @return T[]
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
        $queryBuilder = $this->createQueryBuilder('follow')
            ->select('COUNT(follow)')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.accepted = :accepted')
            ->setParameter('localActor', $localActor)
            ->setParameter('accepted', $accepted);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
