<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\DependencyInjection;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\LocalActorInterface;
use Dontdrinkandroot\ActivityPubOrmBundle\Model\Container\Param;
use Dontdrinkandroot\ActivityPubOrmBundle\Model\Container\Tag;
use Dontdrinkandroot\ActivityPubOrmBundle\Service\LocalObject\LocalObjectEntityProviderInterface;
use InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrActivityPubOrmExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');

        $container
            ->registerForAutoconfiguration(LocalObjectEntityProviderInterface::class)
            ->addTag(Tag::LOCAL_OBJECT_PROVIDER);
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $processedConfig = $this->processConfiguration(new Configuration(), $configs);

        if (
            is_string($localActorClass = $processedConfig['local_actor_class'] ?? null)
            && !is_a($localActorClass, LocalActorInterface::class, true)
        ) {
            throw new InvalidArgumentException(
                sprintf(
                    'Local actor class must implement "%s"',
                    LocalActorInterface::class
                )
            );
        }
        $container->setParameter(Param::LOCAL_ACTOR_CLASS, $localActorClass);

        $container->prependExtensionConfig(
            'doctrine',
            [
                'orm' => [
                    'resolve_target_entities' => [
                        LocalActorInterface::class => $localActorClass
                    ]
                ]
            ]
        );
    }
}
