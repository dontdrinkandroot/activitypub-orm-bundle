<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Person as ActivityPubPerson;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Endpoints;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Entity\StoredActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\FixtureSetDefault;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class StoredObjectResolverTest extends WebTestCase
{
    public function testRemoteActor(): void
    {
        self::bootKernel();
        self::loadFixtures([FixtureSetDefault::class]);

        $uri = Uri::fromString('https://example.com/actor/test');

        $actor = new ActivityPubPerson();
        $actor->id = $uri;
        $actor->name = 'Test Actor';
        $actor->preferredUsername = 'test';
        $actor->inbox = Uri::fromString('https://example.com/actor/test/inbox');
        $actor->endpoints = new Endpoints([
            'sharedInbox' => Uri::fromString('https://example.com/inbox')
        ]);

        $clientMock = $this->createMock(ActivityPubClientInterface::class);
        $clientMock->expects(self::once())->method('request')->willReturn($actor);
        self::getContainer()->set(ActivityPubClientInterface::class, $clientMock);

        $storedObjectResolver = self::getService(StoredObjectResolverInterface::class);
        $resolvedActor = $storedObjectResolver->resolve($uri, StoredActor::class);

        self::assertNotNull($resolvedActor);
        self::assertTrue($uri->equals($resolvedActor->uri));
        self::assertEquals(Uri::fromString('https://example.com/actor/test/inbox'), $resolvedActor->inbox);
        self::assertEquals(Uri::fromString('https://example.com/inbox'), $resolvedActor->sharedInbox);
    }
}
