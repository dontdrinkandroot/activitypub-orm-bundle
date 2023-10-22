<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['test' => true],
    Liip\TestFixturesBundle\LiipTestFixturesBundle::class => ['test' => true],
    Dontdrinkandroot\ActivityPubCoreBundle\DdrActivityPubCoreBundle::class => ['all' => true],
    Dontdrinkandroot\ActivityPubOrmBundle\DdrActivityPubOrmBundle::class => ['all' => true],
];
