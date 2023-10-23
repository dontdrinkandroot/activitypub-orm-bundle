<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Service\Follow;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowerRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\FollowingRepository;
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

        $followerRepository = self::getService(FollowerRepository::class);
        $follower = $followerRepository->findOneByLocalActorAndRemoteActorUri(
            $localActorService,
            Uri::fromString('http://localhost/@person')
        );
        self::assertNotNull($follower);
        self::assertFalse($follower->accepted);

        $followingRepository = self::getService(FollowingRepository::class);
        $following = $followingRepository->findOneByLocalActorAndRemoteActorUri(
            $localActorPerson,
            Uri::fromString('http://localhost/@service'),
        );
        self::assertNotNull($following);
        self::assertFalse($following->accepted);

        $followService->unfollow(
            localActor: $localActorPerson,
            remoteActorId: Uri::fromString('http://localhost/@service')
        );

        $follower = $followerRepository->findOneByLocalActorAndRemoteActorUri(
            $localActorService,
            Uri::fromString('http://localhost/@person'),
        );
        self::assertNull($follower);

        $following = $followingRepository->findOneByLocalActorAndRemoteActorUri(
            $localActorPerson,
            Uri::fromString('http://localhost/@service'),
        );
        self::assertNull($following);
    }
}
