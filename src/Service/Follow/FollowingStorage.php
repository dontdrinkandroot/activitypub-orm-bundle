<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowingStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Following;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowingRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorResolver;

/**
 * @extends AbstractFollowStorage<Following>
 */
class FollowingStorage extends AbstractFollowStorage implements FollowingStorageInterface
{
    public function __construct(
        FollowingRepository $repository,
        DatabaseActorResolver $actorResolver
    ) {
        parent::__construct($repository, $actorResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity(LocalActorInterface $localActor, StoredActor $remoteActor): AbstractFollow
    {
        return new Following($localActor, $remoteActor, false);
    }
}
