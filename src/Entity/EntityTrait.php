<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

trait EntityTrait
{
    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    public function getId(): int
    {
        return $this->id ?? throw new RuntimeException('Entity is not persisted yet');
    }

    public function isPersisted(): bool
    {
        return null !== $this->id;
    }
}
