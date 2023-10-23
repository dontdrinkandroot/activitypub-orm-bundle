<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\ActivityPubCoreBundle\Serializer\ActivityStreamEncoder;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorUriGeneratorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractLocalActorFixture extends Fixture
{
    public function __construct(
        private readonly LocalActorUriGeneratorInterface $localActorUriGenerator,
        private readonly SerializerInterface $serializer,
        private readonly LocalActorServiceInterface $localActorService
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

        $actor = $this->localActorService->toActivityPubActor($localActor);
        $localActor->raw->content = $this->serializer->serialize($actor, ActivityStreamEncoder::FORMAT);

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
