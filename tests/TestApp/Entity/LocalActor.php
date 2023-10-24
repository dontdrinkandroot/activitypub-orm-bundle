<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\EntityTrait;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use RuntimeException;

#[ORM\Entity]
class LocalActor extends StoredActor implements LocalActorInterface
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

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->preferredUsername ?? throw new RuntimeException('No username set');
    }
}
