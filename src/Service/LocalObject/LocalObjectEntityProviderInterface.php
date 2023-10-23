<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;

interface LocalObjectEntityProviderInterface
{
    public function has(Uri $id): bool;

    public function provide(Uri $id): ?StoredObject;
}
