<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Activity;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\Activity as DbActivity;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ActivityRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\DatabaseObjectPersisterInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\ObjectContentStorage;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use Dontdrinkandroot\Common\Asserted;
use Override;

class DatabaseActivityService implements DatabaseObjectPersisterInterface
{
    public function __construct(
        private readonly ActivityRepository $activityRepository,
        private readonly StoredObjectResolverInterface $storedObjectResolver,
        private readonly ObjectContentStorage $objectContentStorage,
    ) {
    }

    #[Override]
    public function persist(object $object): bool
    {
        if (!$object instanceof Activity) {
            return false;
        }

        $activity = $object;
        $dbActor = $this->storedObjectResolver->resolve(Asserted::notNull($activity->actor->getSingleValueId()));
        $dbObject = $this->storedObjectResolver->resolve(Asserted::notNull($activity->object->getId()));
        $dbActivity = new DbActivity(
            uri: $activity->getId(),
            type: $activity->getType(),
            actor: $dbActor,
            object: $dbObject,
            published: $activity->published,
        );
        $this->activityRepository->create($dbActivity);
        $this->objectContentStorage->store($dbActivity, $activity);
    }
}
