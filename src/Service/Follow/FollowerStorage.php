<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follower;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowerRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorResolver;

/**
 * @extends AbstractFollowStorage<Follower>
 */
class FollowerStorage extends AbstractFollowStorage implements FollowerStorageInterface
{
    public function __construct(
        FollowerRepository $repository,
        DatabaseActorResolver $actorResolver
    ) {
        parent::__construct($repository, $actorResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity(LocalActorInterface $localActor, StoredActor $remoteActor): AbstractFollow
    {
        return new Follower($localActor, $remoteActor, false);
    }
}
