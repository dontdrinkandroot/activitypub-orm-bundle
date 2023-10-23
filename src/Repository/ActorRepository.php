<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;

/**
 * @extends CrudServiceEntityRepository<StoredActor>
 */
class ActorRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoredActor::class);
    }

    public function findByUri(Uri $uri): ?StoredActor
    {
        return $this->findOneBy(['uri' => $uri]);
    }
}
