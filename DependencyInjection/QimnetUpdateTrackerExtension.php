<?php

namespace Qimnet\UpdateTrackerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Bundle Configuration
 */
class QimnetUpdateTrackerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('qimnet.update_tracker.entity_name', $config['entity_name']);
        $container->setParameter('qimnet.update_tracker.response.default_max_age', $config['response']['default_max_age']);
        $container->setParameter('qimnet.update_tracker.response.default_shared_max_age', $config['response']['default_shared_max_age']);
        $container->setParameter('qimnet.update_tracker.path_generator.timestamp_argument_name', $config['routing']['timestamp_argument_name']);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        //Do not load cache manager services if the cache manager is disabled
        if ($config['cache_manager']['enabled']) {
            if (!$config['cache_manager']['prefix']) {
                throw new InvalidConfigurationException('Prefix has to be set if cache manager is enabled');
            }
            $container->setParameter('qimnet.update_tracker.cache_manager.ttl', $config['cache_manager']['ttl']);
            $container->setParameter('qimnet.update_tracker.cache_manager.prefix', $config['cache_manager']['prefix']);
            $container->setParameter('qimnet.update_tracker.cache_manager.debug', $config['cache_manager']['debug']);
            $container->setParameter('qimnet.update_tracker.cache_manager.default_repository', $config['cache_manager']['default_repository']);
            $loader->load('cache_manager.yml');
        }
    }
}
