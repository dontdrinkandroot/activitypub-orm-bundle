<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowingStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\AbstractFollow;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Following;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowingRepository;

/**
 * @extends AbstractFollowStorage<Following>
 */
class FollowingStorage extends AbstractFollowStorage implements FollowingStorageInterface
{
    public function __construct(
        FollowingRepository $repository,
    ) {
        parent::__construct($repository);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity(LocalActorInterface $localActor, Uri $remoteActorId): AbstractFollow
    {
        return new Following($localActor, $remoteActorId, false);
    }
}
