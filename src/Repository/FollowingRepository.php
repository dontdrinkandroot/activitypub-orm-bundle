<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Following;

/**
 * @extends CrudServiceEntityRepository<Following>
 */
class FollowingRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Following::class);
    }

}
