<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Person;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor\Service;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalObject\PersonNote1;
use Override;

class FixtureSetDefault extends Fixture implements DependentFixtureInterface
{
    #[Override]
    public function getDependencies(): array
    {
        return [Person::class, Service::class, PersonNote1::class];
    }

    #[Override]
    public function load(ObjectManager $manager): void
    {
        /* Noop */
    }
}
