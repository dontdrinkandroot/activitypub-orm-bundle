<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\MappedSuperclass]
#[ORM\UniqueConstraint(columns: ['local_actor_id', 'remote_actor_uri'])]
abstract class AbstractFollow
{
    use EntityTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(name: 'local_actor_id', nullable: false)]
        public /*readonly*/ LocalActorInterface $localActor,

        #[ORM\Column(name: 'remote_actor_uri', type: UriType::NAME)]
        public /*readonly*/ Uri $remoteActorUri,

        #[ORM\Column(name: 'accepted', type: Types::BOOLEAN)]
        public bool $accepted = false
    ) {
    }
}
