<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\EntityTrait;

#[ORM\Entity]
class LocalActor implements LocalActorInterface
{
    use EntityTrait;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        public string $username,

        #[ORM\Column(type: Types::STRING, enumType: ActorType::class)]
        public ActorType $type,

        #[ORM\Column(type: Types::TEXT)]
        public string $privateKeyPem,

        #[ORM\Column(type: Types::TEXT)]
        public string $publicKeyPem
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}
