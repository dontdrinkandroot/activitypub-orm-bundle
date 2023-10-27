<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected static function loadFixtures(array $classNames = []): ReferenceRepository
    {
        $databaseTool = self::getService(DatabaseToolCollection::class)->get();
        return $databaseTool->loadFixtures($classNames)->getReferenceRepository();
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    protected static function getService(string $class, ?string $id = null): object
    {
        if (null === $id) {
            $id = $class;
        }
        $service = self::getContainer()->get($id);
        self::assertInstanceOf($class, $service);
        return $service;
    }
}
