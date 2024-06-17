<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle;

use Dontdrinkandroot\ActivityPubOrmBundle\DependencyInjection\CompilerPass\RegisterDbalTypesCompilerPass;
use Override;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrActivityPubOrmBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterDbalTypesCompilerPass());
    }
}
