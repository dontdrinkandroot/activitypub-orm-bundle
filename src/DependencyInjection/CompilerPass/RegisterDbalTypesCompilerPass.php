<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\DependencyInjection\CompilerPass;

use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\PlainJsonType;
use Dontdrinkandroot\ActivityPubOrmBundle\Doctrine\Dbal\Type\UriType;
use Dontdrinkandroot\Common\Asserted;
use Override;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterDbalTypesCompilerPass implements CompilerPassInterface
{
    private const string PARAM_NAME = 'doctrine.dbal.connection_factory.types';

    #[Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter(self::PARAM_NAME)) {
            return;
        }

        $types = Asserted::array($container->getParameter(self::PARAM_NAME));

        $types[UriType::NAME] = ['class' => UriType::class];
        $types[PlainJsonType::NAME] = ['class' => PlainJsonType::class];

        $container->setParameter(self::PARAM_NAME, $types);
    }
}
