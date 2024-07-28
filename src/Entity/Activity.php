<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
#[ORM\Table(name: 'activity')]
class Activity extends CoreObject
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

        #[ORM\Column(type: Types::BIGINT, nullable: true)]
        public ?int $published = null,
    ) {
        parent::__construct($uri, $type);
    }
}
