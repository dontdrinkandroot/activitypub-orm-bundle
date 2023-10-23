<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
class Share
{
    use EntityTrait;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    public readonly DateTimeInterface $created;

    public function __construct(
        #[ORM\Column(type: UriType::NAME)]
        public /*readonly*/ Uri $actorId,

        #[ORM\ManyToOne(targetEntity: StoredObject::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /*readonly*/ StoredObject $localObject,

        ?DateTimeInterface $created = null
    ) {
        $this->created = $created ?? new DateTimeImmutable();
    }
}
