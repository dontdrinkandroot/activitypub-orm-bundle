<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\PendingDelivery;

/**
 * @extends CrudServiceEntityRepository<PendingDelivery>
 */
class PendingDeliveryRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PendingDelivery::class);
    }
}
