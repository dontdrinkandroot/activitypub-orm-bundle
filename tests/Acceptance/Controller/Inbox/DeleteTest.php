<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Acceptance\Controller\Inbox;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\SignKey;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Signature\KeyPairGenerator;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\FixtureSetDefault;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class DeleteTest extends WebTestCase
{
    public function testDeleteNotificationOfUnknownUserThatIsAlreadyGone(): void
    {
        self::bootKernel();
        $referenceRepository = self::loadFixtures([FixtureSetDefault::class]);
        $activityPubClient = self::getService(ActivityPubClientInterface::class);
        $localActorService = self::getService(LocalActorServiceInterface::class);

        $keyPair = (new KeyPairGenerator())->generateKeyPair();

        $signKey = new SignKey(
            id: Uri::fromString('https://example.com/@deleteduser#main-key'),
            owner: Uri::fromString('https://example.com/@deleteduser'),
            privateKeyPem: $keyPair->privateKey,
            publicKeyPem: $keyPair->publicKey
        );

        // TODO: Replace once listener is implemented
        $this->expectExceptionCode(501);
        $activityPubClient->request(
            method: 'POST',
            uri: Uri::fromString('http://localhost/inbox'),
            content: json_encode([
                '@context' => 'https://www.w3.org/ns/activitystreams',
                'id' => 'https://example.com/@deleteduser#delete',
                'type' => 'Delete',
                'actor' => 'https://example.com/@deleteduser',
                'to' => [
                    "https://www.w3.org/ns/activitystreams#Public"
                ],
                'object' => 'https://example.com/@deleteduser',
            ], JSON_THROW_ON_ERROR),
            signKey: $signKey
        );
    }
}
