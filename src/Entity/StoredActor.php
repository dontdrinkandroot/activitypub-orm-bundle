<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
#[ORM\Table(name: 'actor')]
class StoredActor extends StoredObject
{
    public function __construct(
        Uri $uri,
        string $type,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $preferredUsername = null,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $domain = null,

        #[ORM\Column(type: Types::STRING, nullable: true)]
        public ?string $name = null,

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        public ?string $summary = null,

        #[ORM\Column(type: UriType::NAME, nullable: true)]
        public ?Uri $inbox = null,

        #[ORM\Column(type: UriType::NAME, nullable: true)]
        public ?Uri $sharedInbox = null,

        #[ORM\Column(type: Types::STRING, length: 512, nullable: true)]
        public ?string $publicKey = null,

    ) {
        parent::__construct($uri, $type);
    }
}
