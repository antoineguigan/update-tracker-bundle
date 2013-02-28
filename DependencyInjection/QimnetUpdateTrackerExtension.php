<?php

namespace Qimnet\UpdateTrackerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
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
