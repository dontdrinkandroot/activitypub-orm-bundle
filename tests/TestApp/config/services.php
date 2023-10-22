<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Config;

use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\HttpClient\KernelBrowserHttpClient;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\LocalActorService;
use Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\LocalObjectResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->load('Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Repository\\', '../Repository/*')
        ->autowire()
        ->autoconfigure();

    $services->load('Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\Service\\', '../Service/*')
        ->autowire()
        ->autoconfigure();

    $services->load('Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\\', '../DataFixtures/*')
        ->autowire()
        ->autoconfigure();

    $services->set(KernelBrowserHttpClient::class)
        ->args([
            service('test.client')
        ]);

//    // TODO: Why is this not tagged automatically?
//    $services->set(LocalNoteService::class)
//        ->autowire()
//        ->autoconfigure()
//        ->tag(Tag::LOCAL_OBJECT_PROVIDER);

    $services->alias(HttpClientInterface::class, KernelBrowserHttpClient::class);
    $services->alias(LocalActorServiceInterface::class, LocalActorService::class);

    /* Public */
    $services->alias(FollowServiceInterface::class, FollowService::class)->public();
};
