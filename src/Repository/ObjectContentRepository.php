<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
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

    public function findOneByUri(Uri $uri): ?ObjectContent
    {
        return $this->createQueryBuilder('objectContent')
            ->join('objectContent.object', 'object')
            ->where('object.uri = :uri')
            ->setParameter('uri', $uri)
            ->getQuery()->getOneOrNullResult();
    }
}
