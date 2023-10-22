<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follower;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowerRepository;
use RuntimeException;

class FollowerStorage implements FollowerStorageInterface
{
    public function __construct(
        private readonly FollowerRepository $repository
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function addRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $follower = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $follower) {
            return;
        }

        $follower = new Follower($localActor, $remoteActorId, false);
        $this->repository->create($follower);
    }

    /**
     * {@inheritdoc}
     */
    public function acceptRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $follower = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $follower) {
            throw new RuntimeException('Follower not found');
        }

        $follower->accepted = true;
        $this->repository->update($follower);
    }

    /**
     * {@inheritdoc}
     */
    public function rejectRequest(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $follower = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null === $follower) {
            throw new RuntimeException('Follower not found');
        }

        $this->repository->delete($follower);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $follower = $this->repository->findOneBy([
            'localActor' => $localActor,
            'remoteActorId' => $remoteActorId
        ]);
        if (null !== $follower) {
            $this->repository->delete($follower);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function list(
        LocalActorInterface $localActor,
        FollowState $followState = FollowState::ACCEPTED,
        int $offset = 0,
        int $limit = 50
    ): array
    {
        $accepted = match($followState) {
            FollowState::ACCEPTED => true,
            FollowState::PENDING => false,
        };

        $followers = $this->repository->findByLocalActorAndAccepted($localActor, $accepted, $offset, $limit);

        return array_map(
            fn(Follower $follower) => $follower->remoteActorId,
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
