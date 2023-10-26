<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

#[ORM\Entity]
class Interaction extends StoredObject
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

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'direction', type: Types::SMALLINT, enumType: Direction::class)]
        public /* readonly */ Direction $direction,
    ) {
        parent::__construct($uri, $type);
    }
}
