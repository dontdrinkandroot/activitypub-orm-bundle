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
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DeliveryService implements DeliveryServiceInterface
{
    public function __construct(
        private readonly PendingDeliveryRepository $pendingDeliveryRepository,
        private readonly LocalActorServiceInterface $localActorService,
        private readonly ActivityPubClientInterface $activityPubClient,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
        public bool $deliverNewMessagesImmediately = false, // TODO: Make configurable (default false)
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function send(LocalActorInterface $localActor, Uri $recipientInbox, CoreType $payload): void
    {
        $pendingDelivery = new PendingDelivery(
            $localActor,
            $recipientInbox,
            $this->serializer->serialize($payload, ActivityStreamEncoder::FORMAT)
        );
        $this->pendingDeliveryRepository->create($pendingDelivery);

        if ($this->deliverNewMessagesImmediately) {
            $this->sendPendingDelivery($pendingDelivery);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendQueued(?int $limit = null): void
    {
        $pendingDeliveries = $this->pendingDeliveryRepository->findBy([], ['nextDelivery' => 'asc'], $limit);
        foreach ($pendingDeliveries as $pendingDelivery) {
            $this->sendPendingDelivery($pendingDelivery);
        }
    }

    private function sendPendingDelivery(PendingDelivery $pendingDelivery): void
    {
        $exception = null;
        $deliveryAttemps = $pendingDelivery->deliveryAttempts;
        try {

            $this->activityPubClient->request(
                'POST',
                $pendingDelivery->recipientInbox,
                $pendingDelivery->payload,
                $this->localActorService->getSignKey($pendingDelivery->localActor)
            );
            $this->pendingDeliveryRepository->delete($pendingDelivery);
        } catch (Exception $e) {
            $exception = $e;
            $pendingDelivery->setLastError($e->getMessage());
            $pendingDelivery->scheduleNextDelivery();
            $this->pendingDeliveryRepository->update($pendingDelivery);
        }

        $this->logger->debug('Sent ActivityPub message', [
            'inbox' => $pendingDelivery->recipientInbox,
            'deliveryAttempts' => $deliveryAttemps,
            'payload' => $pendingDelivery->payload,
            'exception' => $exception
        ]);
    }
}
