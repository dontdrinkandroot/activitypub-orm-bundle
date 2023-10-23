<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\RawType;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredObject;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class LocalNote extends StoredObject
{
    public function __construct(
        Uri $uri,
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /* readonly */ LocalActorInterface $attributedTo,
        #[ORM\Column(type: 'uuid')]
        public Uuid $uuid,
        #[ORM\Column(type: 'text')]
        public string $source,
        RawType $raw,
    ) {
        parent::__construct($uri, 'Note', $raw);
    }
}
