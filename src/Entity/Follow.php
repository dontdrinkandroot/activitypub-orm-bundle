<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;

/**
 * Represents a Follow relationship between a local and another actor (local or remote).
 */
#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['local_actor_id', 'remote_actor_id', 'direction'])]
class Follow
{
    use EntityTrait;

    /**
     * @param Direction $direction Indicates if the local actor is following the remote actor or vice versa.
     * @param FollowState $state Indicates if the follow request has been accepted or is still pending.
     */
    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(name: 'local_actor_id', nullable: false)]
        public readonly LocalActorInterface $localActor,

        #[ORM\ManyToOne(targetEntity: Actor::class)]
        #[ORM\JoinColumn(name: 'remote_actor_id', nullable: false)]
        public readonly Actor $remoteActor,

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'direction', type: Types::SMALLINT, enumType: Direction::class)]
        public readonly Direction $direction,

        // TODO: Would love to use boolean but hydration fails
        #[ORM\Column(name: 'accepted', type: Types::SMALLINT, enumType: FollowState::class)]
        public FollowState $state = FollowState::PENDING
    ) {
    }
}
