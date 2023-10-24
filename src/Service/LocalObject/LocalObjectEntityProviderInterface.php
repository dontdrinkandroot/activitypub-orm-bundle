<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;

interface LocalObjectEntityProviderInterface
{
    public function has(Uri $uri): bool;

    public function provide(Uri $uri): ?StoredObject;
}
