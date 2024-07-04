<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['local_actor_id', 'remote_actor_id', 'direction'])]
class Follow
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(name: 'local_actor_id', nullable: false)]
        public readonly LocalActorInterface $localActor,

        #[ORM\ManyToOne(targetEntity: StoredActor::class)]
        #[ORM\JoinColumn(name: 'remote_actor_id', nullable: false)]
        public readonly StoredActor $remoteActor,

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'direction', type: Types::SMALLINT, enumType: Direction::class)]
        public readonly Direction $direction,

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'accepted', type: Types::SMALLINT, enumType: FollowState::class)]
        public FollowState $state = FollowState::PENDING
    ) {
    }
}
