<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Actor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use Override;
use RuntimeException;

class FollowStorage implements FollowStorageInterface
{
    public function __construct(
        private readonly FollowRepository $repository,
        private readonly StoredObjectResolverInterface $storedObjectResolver,
    ) {
    }

    #[Override]
    public function add(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null !== $follow) {
            return;
        }

        $follow = new Follow($localActor, $remoteActor, $direction);
        $this->repository->create($follow);
    }

    #[Override]
    public function accept(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $follow->state = FollowState::ACCEPTED;
        $this->repository->update($follow);
    }

    #[Override]
    public function reject(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $this->repository->delete($follow);
    }

    #[Override]
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null !== $follow) {
            $this->repository->delete($follow);
        }
    }

    #[Override]
    public function findState(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): ?FollowState
    {
        $remoteActor = $this->storedObjectResolver->resolve($remoteActorId, Actor::class)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            return null;
        }

        return $follow->state;
    }

    #[Override]
    public function list(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        $follows = $this->repository->findAllByLocalActor($localActor, $direction, $followState, $offset, $limit);

        return array_map(
            fn(Follow $follower
            ): Uri => $follower->remoteActor->uri,
            $follows
        );
    }

    #[Override]
    public function count(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED
    ): int
    {
        return $this->repository->countByLocalActor($localActor, $direction, $followState);
    }
}
