<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject;

interface LocalObjectEntityProviderInterface
{
    public function provideEntity(Uri $uri): CoreObject|false|null;
}
