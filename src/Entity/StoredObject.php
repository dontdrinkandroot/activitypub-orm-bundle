<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\Table(name: 'object')]
class StoredObject
{
    use EntityTrait;

    #[ORM\Column(type: Types::BIGINT)]
    public int $updated;

    public function __construct(
        #[ORM\Column(type: UriType::NAME, unique: true)]
        public /* readonly */ Uri $uri,

        #[ORM\Column(type: Types::STRING)]
        public /* readonly */ string $type,

        #[ORM\OneToOne(targetEntity: RawType::class, cascade: ["persist", "remove"], fetch: 'LAZY')]
        public /* readonly */ RawType $raw,
    ) {
    }
}
