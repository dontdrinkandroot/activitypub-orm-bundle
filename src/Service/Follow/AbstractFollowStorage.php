<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\AbstractFollowRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorResolver;
use RuntimeException;

/**
 * @template T of AbstractFollow
 */
abstract class AbstractFollowStorage implements FollowStorageInterface
{
    /**
     * @param AbstractFollowRepository<T> $repository
     */
    public function __construct(
        private readonly AbstractFollowRepository $repository,
        private readonly DatabaseActorResolver $actorResolver
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function add(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemotActor($localActor, $remoteActor);
        if (null !== $follow) {
            return;
        }

        $follow = $this->createEntity($localActor, $remoteActor);
        $this->repository->create($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function accept(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemotActor($localActor, $remoteActor);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $follow->accepted = true;
        $this->repository->update($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemotActor($localActor, $remoteActor);
        if (null === $follow) {
            throw new RuntimeException('Follow not found');
        }

        $this->repository->delete($follow);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(LocalActorInterface $localActor, Uri $remoteActorId): void
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemotActor($localActor, $remoteActor);
        if (null !== $follow) {
            $this->repository->delete($follow);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findState(LocalActorInterface $localActor, Uri $remoteActorId): ?FollowState
    {
        $remoteActor = $this->actorResolver->findOrCreate($remoteActorId)
            ?? throw new RuntimeException('Remote Actor not found');
        $follow = $this->repository->findOneByLocalActorAndRemotActor($localActor, $remoteActor);
        if (null === $follow) {
            return null;
        }

        return match ($follow->accepted) {
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

        $follows = $this->repository->findByLocalActorAndAccepted($localActor, $accepted, $offset, $limit);

        return array_map(
            fn(AbstractFollow $follower) => $follower->remoteActor->uri,
            $follows
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

    /**
     * @return T
     */
    abstract protected function createEntity(LocalActorInterface $localActor, StoredActor $remoteActor): AbstractFollow;
}
