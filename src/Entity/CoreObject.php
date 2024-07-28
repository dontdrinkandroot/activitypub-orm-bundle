<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;
use RuntimeException;

#[ORM\Entity]
#[ORM\InheritanceType(value: 'JOINED')]
#[ORM\Table(name: 'object')]
class CoreObject
{
    use EntityTrait;

    #[ORM\Column(type: Types::BIGINT)]
    protected ?int $updated = null;

    public function __construct(
        #[ORM\Column(type: UriType::NAME, unique: true)]
        public readonly Uri $uri,

        #[ORM\Column(type: Types::STRING)]
        public readonly string $type,
    ) {
    }

    public function getUpdated(): int
    {
        return $this->updated ?? throw new RuntimeException('Entity not persisted yet');
    }
}
