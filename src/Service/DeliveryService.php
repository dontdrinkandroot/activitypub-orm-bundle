<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Service;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\CoreType;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\PendingDelivery;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\PendingDeliveryRepository;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class DeliveryService implements DeliveryServiceInterface
{
    public function __construct(
        private readonly PendingDeliveryRepository $pendingDeliveryRepository,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly ActivityPubClientInterface $activityPubClient,
        private readonly SerializerInterface $serializer,
        private bool $throwExceptions = false
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function send(LocalActorInterface $localActor, Uri $recipientInbox, CoreType $payload): void
    {
        $signKey = $this->localActorService->getSignKey($localActor);

        //TODO: Not send straight away, but queue and send in background
        try {
            $this->activityPubClient->request('POST', $recipientInbox, $payload, $signKey);
        } catch (Exception $e) {
            if ($this->throwExceptions) {
                throw $e;
            }

            $pendingDelivery = new PendingDelivery(
                $localActor,
                $recipientInbox,
                $this->serializer->serialize($payload, ActivityStreamEncoder::FORMAT)
            );
            $this->pendingDeliveryRepository->create($pendingDelivery);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendQueued(?int $limit = null): void
    {
        $pendingDeliveries = $this->pendingDeliveryRepository->findBy([], ['nextDelivery' => 'asc'], $limit);
        foreach ($pendingDeliveries as $pendingDelivery) {
            try {
                $this->activityPubClient->request(
                    'POST',
                    $pendingDelivery->recipientInbox,
                    $pendingDelivery->payload,
                    $this->localActorService->getSignKey($pendingDelivery->localActor)
                );
                $this->pendingDeliveryRepository->delete($pendingDelivery);
            } catch (Exception $e) {
                $pendingDelivery->scheduleNextDelivery();
                $this->pendingDeliveryRepository->update($pendingDelivery);
            }
        }
    }

    public function setThrowExceptions(bool $throwExceptions): void
    {
        $this->throwExceptions = $throwExceptions;
    }
}
