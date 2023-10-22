<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;

/**
 * @extends CrudServiceEntityRepository<Actor>
 */
class ActorRepository extends CrudServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actor::class);
    }

    public function findByUri(Uri $uri): ?Actor
    {
        return $this->findOneBy(['uri' => $uri]);
    }
}
