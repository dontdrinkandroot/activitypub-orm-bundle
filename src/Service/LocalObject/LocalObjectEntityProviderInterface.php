<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;

interface LocalObjectEntityProviderInterface
{
    public function provideEntity(Uri $uri): StoredObject|false|null;
}
