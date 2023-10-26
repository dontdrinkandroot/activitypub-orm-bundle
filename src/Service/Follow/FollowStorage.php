<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Direction;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follow;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorResolver;
use RuntimeException;

class FollowStorage implements FollowStorageInterface
{
    public function __construct(
        private readonly FollowRepository $repository,
        private readonly DatabaseActorResolver $actorResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function add(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null !== $follow) {
            return;
        }

        $follow = new Follow($localActor, $remoteActor, $direction);
        $this->repository->create($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function accept(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $follow->state = FollowState::ACCEPTED;
        $this->repository->update($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $this->repository->delete($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null !== $follow) {
            $this->repository->delete($follow);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findState(LocalActorInterface $localActor, Uri $remoteActorId, Direction $direction): ?FollowState
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemoteActor($localActor, $remoteActor, $direction);
        if (null === $follow) {
            return null;
        }

        return $follow->state;
    }

    /**
     * {@inheritdoc}
     */
    public function list(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        $follows = $this->repository->findAllByLocalActor($localActor, $direction, $followState, $offset, $limit);

        return array_map(
            fn(Follow $follower) => $follower->remoteActor->uri,
            $follows
        );
    }

    /**
     * {@inheritdoc}
     */
    public function count(
        LocalActorInterface $localActor,
        Direction $direction,
        FollowState $followState = FollowState::ACCEPTED
    ): int
    {
        $accepted = match ($followState) {
            FollowState::ACCEPTED => true,
            FollowState::PENDING => false,
        };

        return $this->repository->countByLocalActor($localActor, $direction, $accepted);
    }
}
