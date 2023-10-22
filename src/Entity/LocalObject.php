<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;

#[ORM\Entity]
#[ORM\InheritanceType(value: 'JOINED')]
class LocalObject
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /*readonly*/ LocalActorInterface $localActor,
    ) {
    }
}
