<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\ObjectContent;

/**
 * @extends CrudServiceEntityRepository<ObjectContent>
 */
class ObjectContentRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectContent::class);
    }
}
