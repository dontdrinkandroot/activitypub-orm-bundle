<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalObject;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Property\Uri;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalActor;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Entity\LocalNote;
use Override;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class PersonNote1 extends Fixture implements DependentFixtureInterface
{
    public const string UUID = 'a971cbac-fb77-4192-821f-cc08b706c86e';
    public const string URI = 'http://localhost/notes/a971cbac-fb77-4192-821f-cc08b706c86e';

    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Override]
    public function getDependencies(): array
    {
        return [Person::class];
    }

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $localActor = $this->getReference(Person::class, LocalActor::class);

        $sourceContent = 'This is the content';

        $uri = $this->urlGenerator->generate(
            'ddr.activity_pub_orm.tests.note.get',
            ['uuid' => self::UUID],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $localObject = new LocalNote(
            uri: Uri::fromString($uri),
            attributedTo: $localActor,
            uuid: Uuid::fromString(self::UUID),
            source: $sourceContent,
        );

        $manager->persist($localObject);
        $manager->flush();
        $this->addReference(self::class, $localObject);
    }
}
