<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['local_actor_id', 'remote_actor_id'])]
class Follower
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /*readonly*/ LocalActorInterface $localActor,

        #[ORM\Column(type: UriType::NAME)]
        public /*readonly*/ Uri $remoteActorId,

        #[ORM\Column(type: Types::BOOLEAN)]
        public bool $accepted = false
    ) {
    }
}
