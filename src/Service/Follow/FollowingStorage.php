<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowingStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Following;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowingRepository;
use RuntimeException;

class FollowingStorage implements FollowingStorageInterface
{
    public function __construct(
        private readonly FollowingRepository $repository,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $following) {
            return;
        }

        $following = new Following($localActor, $remoteActorId, false);
        $this->repository->create($following);
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccepted(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $following) {
            throw new RuntimeException('Following not found');
        }

        $following->accepted = true;
        $this->repository->update($following);
    }

    /**
     * {@inheritdoc}
     */
    public function requestRejected(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $following) {
            throw new RuntimeException('Following not found');
        }

        $this->repository->delete($following);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $following) {
            $this->repository->delete($following);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAccepted(LocalActorInterface $localActor, Uri $remoteActorId): bool
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);

        return null !== $following && $following->accepted;
    }

    /**
     * {@inheritdoc}
     */
    public function findState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState
    {
        $following = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);

        if (null === $following) {
            return null;
        }

        return match ($following->accepted) {
            true => FollowState::ACCEPTED,
            false => FollowState::PENDING,
        };
    }

    /**
     * {@inheritdoc}
     */
    public function list(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array {
        $accepted = match ($followState) {
            FollowState::ACCEPTED => true,
            FollowState::PENDING => false,
        };

        $followers = $this->repository->findByLocalActorAndAccepted($localActor, $accepted, $offset, $limit);

        return array_map(
            fn(Following $follower) => $follower->remoteActorId,
            $followers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function count(LocalActorInterface $localActor, FollowState $followState = FollowState::ACCEPTED): int
    {
        $accepted = match ($followState) {
            FollowState::ACCEPTED => true,
            FollowState::PENDING => false,
        };

        return $this->repository->countByLocalActor($localActor, $accepted);
    }
}
