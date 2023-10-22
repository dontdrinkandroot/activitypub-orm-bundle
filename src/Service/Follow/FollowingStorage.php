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
        private readonly FollowingRepository $followingRepository,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->followingRepository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $following) {
            return;
        }

        $following = new Following($localActor, $remoteActorId, false);
        $this->followingRepository->create($following);
    }

    /**
     * {@inheritdoc}
     */
    public function requestAccepted(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->followingRepository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $following) {
            throw new RuntimeException('Following not found');
        }

        $following->accepted = true;
        $this->followingRepository->update($following);
    }

    /**
     * {@inheritdoc}
     */
    public function requestRejected(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->followingRepository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $following) {
            throw new RuntimeException('Following not found');
        }

        $this->followingRepository->delete($following);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $following = $this->followingRepository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $following) {
            $this->followingRepository->delete($following);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isAccepted(LocalActorInterface $localActor, Uri $remoteActorId): bool
    {
        $following = $this->followingRepository->findOneBy([
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
        $following = $this->followingRepository->findOneBy([
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
}
