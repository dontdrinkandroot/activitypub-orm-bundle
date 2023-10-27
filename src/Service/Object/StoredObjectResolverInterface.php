<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;

interface StoredObjectResolverInterface
{
    /**
     * @template T of StoredObject
     * @param Uri $uri
     * @param class-string<T> $type
     * @return T|null
     */
    public function resolve(Uri $uri, string $type = StoredObject::class): ?StoredObject;
}
