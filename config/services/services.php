<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\ActorResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\LocalObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\ShareServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Event\Listener\StoredObjectUpdatedListener;
use Dontdrinkandroot\ActivityPubOrmBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ObjectContentRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredActorRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorResolver;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\DeliveryService;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow\FollowStorage;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityResolver;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\ShareService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Serializer\SerializerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->load('Dontdrinkandroot\ActivityPubOrmBundle\Repository\\', '../../src/Repository/*')
        ->autowire()
        ->autoconfigure();

    $services->load('Dontdrinkandroot\ActivityPubOrmBundle\Service\\', '../../src/Service/*')
        ->autowire()
        ->autoconfigure();

    $services->set(LocalObjectEntityResolver::class)
        ->args([
            tagged_iterator(Tag::LOCAL_OBJECT_PROVIDER)
        ]);

    $services->set(DatabaseActorResolver::class)
        ->args([
            service(ActivityPubClientInterface::class),
            service(StoredActorRepository::class),
            service('cache.app'),
            service(SerializerInterface::class),
            service(ObjectContentRepository::class)
        ]);
    $services->alias(ActorResolverInterface::class, DatabaseActorResolver::class);

    $services->set(StoredObjectUpdatedListener::class)
        ->tag('doctrine.event_listener', ['event' => 'prePersist'])
        ->tag('doctrine.event_listener', ['event' => 'preUpdate']);

    $services->alias(LocalObjectEntityResolverInterface::class, LocalObjectEntityResolver::class);
    $services->alias(LocalObjectResolverInterface::class, LocalObjectEntityResolver::class);
    $services->alias(FollowStorageInterface::class, FollowStorage::class);
    $services->alias(DeliveryServiceInterface::class, DeliveryService::class);
    $services->alias(ShareServiceInterface::class, ShareService::class);
    $services->alias(FollowServiceInterface::class, FollowService::class);
};
