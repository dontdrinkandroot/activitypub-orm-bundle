<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Interaction;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\InteractionRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use Override;
use RuntimeException;

class InteractionService implements InteractionServiceInterface
{
    public function __construct(
        private readonly InteractionRepository $shareRepository,
        private readonly LocalObjectEntityResolverInterface $localObjectEntityResolver,
        private readonly StoredObjectResolverInterface $storedObjectResolver,
    ) {
    }

    #[Override]
    public function incoming(Uri $uri, string $type, Uri $remoteActorId, Uri $localObjectId): void
    {
        $actor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found for uri ' . $remoteActorId);
        $localObject = $this->localObjectEntityResolver->resolve($localObjectId)
            ?? throw new RuntimeException('Local object not found for uri ' . $localObjectId);
        $share = new Interaction($uri, $type, $actor, $localObject, Direction::INCOMING);
        $this->shareRepository->create($share);
    }
}
