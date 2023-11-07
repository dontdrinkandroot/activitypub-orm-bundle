<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;

#[ORM\Entity]
class PendingDelivery
{
    use EntityTrait;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $nextDelivery;

    #[ORM\Column(type: Types::INTEGER)]
    public int $deliveryAttempts = 0;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $lastError = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: LocalActorInterface::class)]
        #[ORM\JoinColumn(nullable: false)]
        public /* readonly */ LocalActorInterface $localActor,

        #[ORM\Column(type: UriType::NAME, length: 255)]
        public /* readonly */ Uri $recipientInbox,

        #[ORM\Column(type: PlainJsonType::NAME)]
        public /* readonly */ string $payload
    ) {
        $this->nextDelivery = new DateTimeImmutable();
    }

    public function scheduleNextDelivery(): void
    {
        $this->nextDelivery = new DateTimeImmutable(
            sprintf(
                '+%d seconds',
                2 ** ($this->deliveryAttempts + 4)
            )
        );
        $this->deliveryAttempts++;
    }

    public function setLastError(?string $lastError): void
    {
        $this->lastError = substr($lastError, 0, 255);
    }
}
