<?php

namespace Qimnet\UpdateTrackerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('qimnet_update_tracker');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_max_age')
                    ->defaultValue(60)
                ->end()
                ->scalarNode('default_shared_max_age')
                    ->defaultValue(60)
                ->end()
                ->scalarNode('entity_name')
                    ->defaultNull()
                ->end()
            ->end()
            ->end();
        return $treeBuilder;
    }
}
