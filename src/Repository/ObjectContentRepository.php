<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\ObjectContent;

/**
 * @extends AbstractFollowRepository<ObjectContent>
 */
class ObjectContentRepository extends AbstractFollowRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectContent::class);
    }
}
