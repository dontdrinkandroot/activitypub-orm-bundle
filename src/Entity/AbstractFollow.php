<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;

#[ORM\MappedSuperclass]
#[ORM\UniqueConstraint(columns: ['local_actor_id', 'remote_actor_id'])]
abstract class AbstractFollow
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(name: 'local_actor_id', nullable: false)]
        public /*readonly*/ LocalActorInterface $localActor,

        #[ORM\ManyToOne(targetEntity: StoredActor::class)]
        #[ORM\JoinColumn(name: 'remote_actor_id', nullable: false)]
        public /*readonly*/ StoredActor $remoteActor,

        #[ORM\Column(name: 'accepted', type: Types::BOOLEAN)]
        public bool $accepted = false
    ) {
    }
}
