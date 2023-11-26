<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\DeliveryService;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\FixtureSetDefault;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Service;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class FollowServiceTest extends WebTestCase
{
    public function testFollowUnfollow(): void
    {
        $referenceRepository = self::loadFixtures([FixtureSetDefault::class]);

        $deliveryService = self::getService(DeliveryService::class);
        $deliveryService->deliverNewMessagesImmediately = true;
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $followService = self::getService(FollowServiceInterface::class);

        $person = $referenceRepository->getReference(Person::class, LocalActor::class);
        $service = $referenceRepository->getReference(Service::class, LocalActor::class);

        $followService->follow(
            localActor: $person,
            remoteActorId: Uri::fromString('https://localhost/@service')
        );

        $followState = $followService->findFollowerState(
            localActor: $service,
            remoteActorId: Uri::fromString('https://localhost/@person')
        );
        self::assertEquals(FollowState::ACCEPTED, $followState);

        $followState = $followService->findFollowingState(
            localActor: $person,
            remoteActorId: Uri::fromString('https://localhost/@service')
        );
        self::assertEquals(FollowState::ACCEPTED, $followState);

        /* Not sure why, but we have to reload here */
        $person = $localActorService->findLocalActorByUsername('person');
        self::assertNotNull($person);
        $service = $localActorService->findLocalActorByUsername('service');
        self::assertNotNull($service);

        $followService->unfollow(
            localActor: $person,
            remoteActorId: Uri::fromString('https://localhost/@service')
        );

        $followState = $followService->findFollowerState(
            localActor: $service,
            remoteActorId: Uri::fromString('https://localhost/@person')
        );
        self::assertNull($followState);

        $followState = $followService->findFollowingState(
            localActor: $person,
            remoteActorId: Uri::fromString('https://localhost/@service')
        );
        self::assertNull($followState);
    }
}
