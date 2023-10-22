<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_activity_pub_orm');
        $rootNode = Asserted::instanceOf($treeBuilder->getRootNode(), ArrayNodeDefinition::class);

        $rootNode
            ->children()
            ->append((new ScalarNodeDefinition('local_actor_class'))->isRequired())
            ->end();

        return $treeBuilder;
    }
}
