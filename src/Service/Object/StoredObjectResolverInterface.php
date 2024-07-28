<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject as DbObject;

interface StoredObjectResolverInterface
{
    /**
     * @template T of Object
     * @param Uri $uri
     * @param class-string<T> $type
     * @return T|null
     */
    public function resolve(Uri $uri, string $type = DbObject::class): ?DbObject;
}
