<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Event\Listener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\CoreObject;
use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\Common\ReflectionUtils;

class StoredObjectUpdatedListener
{
    public function prePersist(PrePersistEventArgs $event): void
    {
        $this->updateTimestamp($event);
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $this->updateTimestamp($event);
    }

    public function updateTimestamp(LifecycleEventArgs $event): void
    {
        $entity = $event->getObject();
        if ($entity instanceof CoreObject) {
            ReflectionUtils::setPropertyValue($entity, 'updated', DateUtils::currentMillis());
        }
    }
}
