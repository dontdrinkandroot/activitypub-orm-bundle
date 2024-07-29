<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;

#[ORM\Entity]
class Interaction extends CoreObject
{
    public function __construct(
        Uri $uri,

        string $type,

        #[ORM\ManyToOne(targetEntity: Actor::class)]
        #[ORM\JoinColumn(nullable: false)]
        public readonly Actor $actor,

        #[ORM\ManyToOne(targetEntity: CoreObject::class)]
        #[ORM\JoinColumn(nullable: false)]
        public readonly CoreObject $object,

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'direction', type: Types::SMALLINT, enumType: Direction::class)]
        public readonly Direction $direction,
    ) {
        parent::__construct($uri, $type);
    }
}
