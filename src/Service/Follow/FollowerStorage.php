<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowerStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Follower;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowerRepository;

/**
 * @extends AbstractFollowStorage<Follower>
 */
class FollowerStorage extends AbstractFollowStorage implements FollowerStorageInterface
{
    public function __construct(
        FollowerRepository $repository
    ) {
        parent::__construct($repository);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity(LocalActorInterface $localActor, Uri $remoteActorId): AbstractFollow
    {
        return new Follower($localActor, $remoteActorId, false);
    }
}
