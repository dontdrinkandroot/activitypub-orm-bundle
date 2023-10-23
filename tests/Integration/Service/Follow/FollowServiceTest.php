<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\FollowState;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Service;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class FollowServiceTest extends WebTestCase
{
    public function testFollowUnfollow(): void
    {
        $referenceRepository = $this->loadFixtures([Person::class, Service::class]);
        $localActorPerson = $referenceRepository->getReference(Person::class, LocalActor::class);
        $localActorService = $referenceRepository->getReference(Service::class, LocalActor::class);

        $followService = self::getService(FollowServiceInterface::class);
        $followService->follow(
            localActor: $localActorPerson,
            remoteActorId: Uri::fromString('http://localhost/@service')
        );

        $followState = $followService->findFollowerState(
            localActor: $localActorService,
            remoteActorId: Uri::fromString('http://localhost/@person')
        );
        self::assertEquals(FollowState::PENDING, $followState);

        $followState = $followService->findFollowingState(
            localActor: $localActorPerson,
            remoteActorId: Uri::fromString('http://localhost/@service')
        );
        self::assertEquals(FollowState::PENDING, $followState);

        $followService->unfollow(
            localActor: $localActorPerson,
            remoteActorId: Uri::fromString('http://localhost/@service')
        );

        $followState = $followService->findFollowerState(
            localActor: $localActorService,
            remoteActorId: Uri::fromString('http://localhost/@person')
        );
        self::assertNull($followState);

        $followState = $followService->findFollowingState(
            localActor: $localActorPerson,
            remoteActorId: Uri::fromString('http://localhost/@service')
        );
        self::assertNull($followState);
    }
}
