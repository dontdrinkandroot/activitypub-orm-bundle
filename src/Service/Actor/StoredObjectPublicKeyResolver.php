<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\PublicKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\PublicKeyResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use RuntimeException;

class StoredObjectPublicKeyResolver implements PublicKeyResolverInterface
{
    public function __construct(private readonly StoredObjectResolverInterface $objectResolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Uri $keyId): PublicKey
    {
        $actorId = $keyId->withFragment(null);

        $publicKeyPem = $this->objectResolver->resolve($actorId, StoredActor::class)->publicKey
            ?? throw new RuntimeException('Could not resolve storedActor for keyId: ' . $keyId);

        return new PublicKey(
            id: $keyId,
            owner: $actorId,
            publicKeyPem: $publicKeyPem
        );
    }
}
