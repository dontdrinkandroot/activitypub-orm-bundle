<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
class RawType
{
    use EntityTrait;

    public function __construct(
        #[ORM\Column(type: UriType::NAME)]
        public /* readonly */ Uri $uri,

        #[ORM\Column(type: Types::STRING)]
        public string $type,

        #[ORM\Column(type: PlainJsonType::NAME)]
        public string $content
    ) {
    }
}
