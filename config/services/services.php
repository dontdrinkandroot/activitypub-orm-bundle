<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Config\Services;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Container\TagName as CoreTagName;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\LocalActorServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Actor\PublicKeyResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Client\ActivityPubClientInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Delivery\DeliveryServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowService;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowServiceInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Follow\FollowStorageInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Object\ObjectResolverInterface;
use Dontdrinkandroot\ActivityPubCoreBundle\Service\Share\InteractionServiceInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Event\Listener\StoredObjectUpdatedListener;
use Dontdrinkandroot\ActivityPubOrmBundle\Model\Container\TagName;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\ObjectContentRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\PendingDeliveryRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredActorRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Repository\StoredObjectRepository;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\DatabaseActorService;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Actor\StoredObjectPublicKeyResolver;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\DeliveryService;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Follow\FollowStorage;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\InteractionService;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityResolver;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityResolverInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\GenericDatabaseObjectProvider;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\ObjectContentStorage;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\ObjectContentStorageInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolver;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\Object\StoredObjectResolverInterface;
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
            tagged_iterator(TagName::LOCAL_OBJECT_PROVIDER)
        ]);

    $services->set(StoredObjectPublicKeyResolver::class)
        ->args([
            service(StoredObjectResolverInterface::class)
        ]);
    $services->alias(PublicKeyResolverInterface::class, StoredObjectPublicKeyResolver::class);

    $services->set(DeliveryService::class)
        ->args([
            service(PendingDeliveryRepository::class),
            service(LocalActorServiceInterface::class),
            service(ActivityPubClientInterface::class),
            service(SerializerInterface::class),
            service('logger'),
        ])
        ->tag('mono_logger.logger', ['channel' => 'activitypub']);
    $services->alias(DeliveryServiceInterface::class, DeliveryService::class);

    $services->set(DatabaseActorService::class)
        ->args([
            service(StoredActorRepository::class),
            service(ObjectContentStorageInterface::class),
            service(StoredObjectResolverInterface::class)
        ])
        ->tag(TagName::DATABASE_OBJECT_PERSISTER, ['priority' => -128])
        ->tag(CoreTagName::OBJECT_PROVIDER, ['priority' => -64]);

    $services->set(StoredObjectUpdatedListener::class)
        ->tag('doctrine.event_listener', ['event' => 'prePersist'])
        ->tag('doctrine.event_listener', ['event' => 'preUpdate']);

    $services->set(GenericDatabaseObjectProvider::class)
        ->args([
            service(ActivityPubClientInterface::class),
            service(StoredObjectRepository::class),
            service(ObjectContentStorageInterface::class),
            tagged_iterator(TagName::DATABASE_OBJECT_PERSISTER)
        ])
        ->tag(CoreTagName::OBJECT_PROVIDER, ['priority' => -128])
        ->tag(TagName::DATABASE_OBJECT_PERSISTER, ['priority' => -256]);

    $services->set(ObjectContentStorage::class)
        ->args([
            service(ObjectContentRepository::class),
            service(StoredObjectRepository::class),
            service(SerializerInterface::class)
        ]);
    $services->alias(ObjectContentStorageInterface::class, ObjectContentStorage::class);

    $services->set(StoredObjectResolver::class)
        ->args([
            service(StoredObjectRepository::class),
            service(ObjectResolverInterface::class)
        ]);
    $services->alias(StoredObjectResolverInterface::class, StoredObjectResolver::class);

    $services->alias(LocalObjectEntityResolverInterface::class, LocalObjectEntityResolver::class);
    $services->alias(FollowStorageInterface::class, FollowStorage::class);
    $services->alias(InteractionServiceInterface::class, InteractionService::class);
    $services->alias(FollowServiceInterface::class, FollowService::class);
};
