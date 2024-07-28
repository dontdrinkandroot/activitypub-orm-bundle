<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject as DbObject;

interface ObjectContentStorageInterface
{
    public function store(Uri|DbObject $object, CoreObject|string $content): void;

    /**
     * @template T of CoreObject
     * @param Uri $uri
     * @param class-string<T> $type
     * @return T|null
     */
    public function find(Uri $uri, string $type = CoreObject::class): ?CoreObject;

    public function findContent(Uri $uri): ?string;

    /**
     * @template T of CoreObject
     * @param Uri $uri
     * @param class-string<T> $type
     * @return T
     */
    public function fetch(Uri $uri, string $type = CoreObject::class): CoreObject;

    public function fetchContent(Uri $uri): string;

    public function delete(Uri $uri): void;
}
