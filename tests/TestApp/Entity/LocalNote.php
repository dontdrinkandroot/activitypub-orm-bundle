<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class LocalNote extends CoreObject
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
    ) {
        parent::__construct($uri, 'Note');
    }
}
