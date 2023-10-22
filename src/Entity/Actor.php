<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
class Actor
{
    use EntityTrait;

    public function __construct(
        #[ORM\Column(type: UriType::NAME, unique: true)]
        public /* readonly */ Uri $uri,

        #[ORM\Column(type: Types::STRING, enumType: ActorType::class)]
        public /* readonly */ ActorType $type,

        #[ORM\OneToOne(targetEntity: RawType::class, cascade: ["persist", "remove"], fetch: 'LAZY')]
        public /* readonly */ RawType $raw,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $preferredUsername = null,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $name = null,

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        public ?string $summary = null,
//
        #[ORM\Column(type: UriType::NAME, nullable: true)]
        public ?Uri $inbox = null,

        #[ORM\Column(type: Types::STRING, length: 512, nullable: true)]
        public ?string $publicKey = null,

    ) {
    }
}
