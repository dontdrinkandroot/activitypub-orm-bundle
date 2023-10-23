<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;

interface LocalObjectEntityResolverInterface extends LocalObjectResolverInterface
{
    public function resolve(Uri $uri): ?StoredObject;
}
