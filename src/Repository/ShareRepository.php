<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Share;

/**
 * @extends CrudServiceEntityRepository<Share>
 */
class ShareRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Share::class);
    }
}
