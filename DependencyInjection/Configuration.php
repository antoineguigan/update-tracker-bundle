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
                ->scalarNode('entity_name')
                    ->defaultNull()
                    ->info('The entity in which the updates should be stored.')
                ->end()
                ->arrayNode('cache_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('True to enable the cache manager.')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('debug')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('prefix')
                            ->info('The prefix of the application for the cache.')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('default_repository')
                            ->defaultValue('apc')
                        ->end()
                        ->scalarNode('ttl')
                            ->info('Default ttl for cache elements.')
                            ->defaultValue(86400)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('response')
                    ->info('Defaults for generated responses.')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_max_age')
                            ->defaultValue(60)
                        ->end()
                        ->scalarNode('default_shared_max_age')
                            ->defaultValue(60)
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->end();
        return $treeBuilder;
    }
}
