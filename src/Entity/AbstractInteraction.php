<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

#[ORM\MappedSuperclass]
class AbstractInteraction extends StoredObject
{
    public function __construct(
        Uri $uri,
        string $type,

        #[ORM\ManyToOne(targetEntity: StoredActor::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /*readonly*/ StoredActor $actor,

        #[ORM\ManyToOne(targetEntity: StoredObject::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /*readonly*/ StoredObject $object,
    ) {
        parent::__construct($uri, $type);
    }
}
