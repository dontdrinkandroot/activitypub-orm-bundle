<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;

/**
 * @template T of AbstractFollow
 */
abstract class AbstractFollowRepository extends CrudServiceEntityRepository
{
    /**
     * @return T|null
     */
    public function findOneByLocalActorAndRemoteActorUri(
        LocalActorInterface $localActor,
        Uri $remoteActorUri
    ): ?AbstractFollow {
        $queryBuilder = $this->createQueryBuilder('follow')
            ->where('follow.localActor = :localActor')
            ->andWhere('follow.remoteActorUri = :remoteActorUri')
            ->setParameter('localActor', $localActor)
            ->setParameter('remoteActorUri', $remoteActorUri);

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
