<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Acceptance\Controller\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ShareRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Service;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalObject\PersonNote1;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class AnnounceTest extends WebTestCase
{
    public function testIncomingAnnounce(): void
    {
        self::bootKernel();
        $this->loadFixtures([Person::class, Service::class, PersonNote1::class]);
        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);
        $service = $localActorService->findLocalActorByUsername('service');
        self::assertNotNull($service);
        $signKey = $localActorService->getSignKey($service);

        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/@person/inbox'),
            content: json_encode([
                '@context' => 'https://www.w3.org/ns/activitystreams',
//                'id' => 'https://example.com/activities/1',
                'type' => 'Announce',
                'actor' => 'http://localhost/@service',
                'object' => PersonNote1::URI,
            ], JSON_THROW_ON_ERROR),
            signKey: $signKey
        );

        $shareRepository = self::getService(ShareRepository::class);
        $shared = $shareRepository->findAll();
        self::assertCount(1, $shared);
        self::assertEquals('http://localhost/@service', $shared[0]->actorId);
    }
}
