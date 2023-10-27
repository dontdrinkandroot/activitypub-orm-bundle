<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Acceptance\Controller\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\InteractionRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\FixtureSetDefault;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalObject\PersonNote1;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class AnnounceTest extends WebTestCase
{
    public function testIncomingAnnounce(): void
    {
        self::bootKernel();
        $referenceRepository = self::loadFixtures([FixtureSetDefault::class]);
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
                'id' => 'https://localhost/interactions/12345',
                'type' => 'Announce',
                'actor' => 'https://localhost/@service',
                'object' => PersonNote1::URI,
            ], JSON_THROW_ON_ERROR),
            signKey: $signKey
        );

        $shareRepository = self::getService(InteractionRepository::class);
        $shares = $shareRepository->findAll();
        self::assertCount(1, $shares);
        $share = $shares[0];
        self::assertEquals('https://localhost/@service', $share->actor->uri);
    }
}
