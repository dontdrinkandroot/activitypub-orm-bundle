<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Integration\Service\Object;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\Actor;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Object\Note;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalObject\PersonNote1;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class ObjectResolverTest extends WebTestCase
{
    public function testResolveLocalNote(): void
    {
        self::bootKernel();
        self::loadFixtures([PersonNote1::class]);

        /* Client must not be called, object is resolved directly */
        $clientMock = $this->createMock(ActivityPubClientInterface::class);
        $clientMock->expects(self::never())->method('request');
        self::getContainer()->set(ActivityPubClientInterface::class, $clientMock);

        $objectResolver = self::getService(ObjectResolverInterface::class);
        $uri = Uri::fromString(PersonNote1::URI);
        $note = $objectResolver->resolveTyped($uri, Note::class);

        self::assertNotNull($note);
        self::assertTrue($uri->equals($note->getId()));
    }

    public function testResolveLocalActor(): void
    {
        self::bootKernel();
        self::loadFixtures([Person::class]);

        /* Client must not be called, object is resolved directly */
        $clientMock = $this->createMock(ActivityPubClientInterface::class);
        $clientMock->expects(self::never())->method('request');
        self::getContainer()->set(ActivityPubClientInterface::class, $clientMock);

        $objectResolver = self::getService(ObjectResolverInterface::class);
        $uri = Uri::fromString('https://localhost/@person');
        $person = $objectResolver->resolveTyped($uri, Actor::class);

        self::assertNotNull($person);
        self::assertTrue($uri->equals($person->getId()));
    }
}
