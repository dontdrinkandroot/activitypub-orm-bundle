<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject;

interface LocalObjectEntityResolverInterface
{
    public function resolve(Uri $uri): ?CoreObject;
}
