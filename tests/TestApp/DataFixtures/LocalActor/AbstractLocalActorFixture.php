<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\Common\Asserted;

abstract class AbstractLocalActorFixture extends Fixture
{
    public function __construct(
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $username = $this->getUsername();
        $uri = $this->localActorUriGenerator->generateId($username);
        $inbox = $this->localActorUriGenerator->generateInbox($username);

        $localActor = new LocalActor(
            uri: $uri,
            type: $this->getType(),
            preferredUsername: $username,
            domain: Asserted::notNull($uri->host),
            inbox: $inbox,
            publicKey: $this->getPublicKeyPem(),
            privateKey: $this->getPrivateKeyPem()
        );

        $manager->persist($localActor);
        $manager->flush();
        $this->addReference(static::class, $localActor);
    }

    /**
     * {@inheritdoc}
     */
    abstract protected function getPrivateKeyPem(): string;

    /**
     * {@inheritdoc}
     */
    abstract protected function getPublicKeyPem(): string;

    abstract protected function getUsername(): string;

    abstract protected function getType(): string;
}
