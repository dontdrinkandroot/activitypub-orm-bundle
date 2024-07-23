<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Core\Activity;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Inbox\InboxServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\InboxItem;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\InboxItemRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use Dontdrinkandroot\Common\DateUtils;
use Override;
use RuntimeException;

class DbInboxService implements InboxServiceInterface
{
    public function __construct(
        private readonly InboxItemRepository $inboxItemRepository,
        private readonly StoredObjectResolverInterface $storedObjectResolver
    ) {
    }

    #[Override]
    public function addItem(LocalActorInterface $localActor, Uri|Activity $activity): void
    {
        $uri = $activity instanceof Uri ? $activity : $activity->getId();
        $storedActivity = $this->storedObjectResolver->resolve($uri)
            ?? throw new RuntimeException('Could not resolve activity with uri: ' . $uri);

        // TODO: Take the creation time of the activity if available?
        $this->inboxItemRepository->create(new InboxItem($localActor, $storedActivity, DateUtils::currentMillis()));
    }
}
