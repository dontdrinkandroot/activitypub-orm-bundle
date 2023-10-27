<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\CoreObject;

interface DatabaseObjectPersisterInterface
{
    /**
     * @template T of CoreObject
     * @param T $object
     * @return bool
     */
    public function persist(object $object): bool;
}
