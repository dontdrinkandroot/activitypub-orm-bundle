<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\LocalObject;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class LocalNote extends LocalObject
{
    public function __construct(
        LocalActorInterface $localActor,

        #[ORM\Column(type: 'uuid')]
        public Uuid $uuid,

        #[ORM\Column(type: 'text')]
        public string $content,
    ) {
        parent::__construct($localActor);
    }
}
