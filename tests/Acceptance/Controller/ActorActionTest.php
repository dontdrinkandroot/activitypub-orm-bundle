<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\Acceptance\Controller;

use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\FixtureSetDefault;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\WebTestCase;

class ActorActionTest extends WebTestCase
{
    public function testMissingActor(): void
    {
        $client = static::createClient();
        self::loadFixtures();

        $client->request('GET', '/@missing');
        $response = $client->getResponse();
        self::assertEquals(404, $response->getStatusCode());
    }

    public function testActor(): void
    {
        $client = static::createClient();
        self::loadFixtures([FixtureSetDefault::class]);

        $client->request('GET', '/@person');
        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals('application/activity+json', $response->headers->get('Content-Type'));
        $json = $response->getContent();
        self::assertIsString($json);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        self::assertNotNull($data['publicKey']['publicKeyPem'] ?? null);
        unset($data['publicKey']['publicKeyPem']);

        self::assertEquals([
            '@context' => [
                'https://www.w3.org/ns/activitystreams',
                'https://w3id.org/security/v1',
            ],
            'id' => 'https://localhost/@person',
            'inbox' => 'https://localhost/@person/inbox',
            'preferredUsername' => 'person',
//            'name' => 'Person',
//            'published' => '2000-01-02T03:04:05Z',
//            'summary' => 'A person',
            'publicKey' => [
                'id' => 'https://localhost/@person#main-key',
                'owner' => 'https://localhost/@person'
            ],
            'type' => 'Person'
        ], $data);
    }
}
