<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;
use Override;
use RuntimeException;
use Stringable;

#[ORM\Entity]
class LocalActor extends Actor implements LocalActorInterface, Stringable
{
    public function __construct(
        Uri $uri,
        string $type,
        string $preferredUsername,
        string $domain,
        Uri $inbox,
        string $publicKey,
        #[ORM\Column(type: Types::TEXT)]
        public string $privateKey,
        ?string $name = null,
        ?string $summary = null,
    ) {
        parent::__construct(
            uri: $uri,
            type: $type,
            preferredUsername: $preferredUsername,
            domain: $domain,
            name: $name,
            summary: $summary,
            inbox: $inbox,
            publicKey: $publicKey
        );
    }

    #[Override]
    public function getUsername(): string
    {
        return $this->preferredUsername ?? throw new RuntimeException('No username set');
    }

    #[Override]
    public function __toString(): string
    {
        return sprintf(
            'LocalActor(id: %s, uri: %s, type: %s)',
            $this->getId(),
            $this->uri->toString(),
            $this->type
        );
    }
}
