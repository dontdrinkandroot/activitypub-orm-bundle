<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\InboxItemRepository;

#[ORM\Entity(repositoryClass: InboxItemRepository::class)]
#[ORM\Table('inbox')]
class InboxItem
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(name: 'local_actor_id', nullable: false)]
        public readonly LocalActorInterface $localActor,

        #[ORM\ManyToOne(targetEntity: StoredObject::class)]
        #[ORM\JoinColumn(name: 'activity_id', nullable: false)]
        public readonly StoredObject $activity,

        #[ORM\Column(type: Types::BIGINT)]
        public int $created
    ) {
    }
}
