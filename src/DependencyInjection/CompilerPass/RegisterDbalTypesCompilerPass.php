<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\DependencyInjection\CompilerPass;

use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterDbalTypesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('doctrine.dbal.connection_factory.types')) {
            return;
        }

        $types = Asserted::array($container->getParameter('doctrine.dbal.connection_factory.types'));

        $types[UriType::NAME] = ['class' => UriType::class, 'commented' => true];
        $types[PlainJsonType::NAME] = ['class' => PlainJsonType::class, 'commented' => true];

        $container->setParameter('doctrine.dbal.connection_factory.types', $types);
    }
}
