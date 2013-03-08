<?php
namespace Qimnet\UpdateTrackerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds Repositories to the "qimnet.update_tracker.cache_repositories" service
 * 
 * Repository services shoud be tagged with "qimnet.update_tracker.cache_repository".
 */
class CacheRepositoryCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('qimnet.update_tracker.cache_repositories')) return;
        $definition = $container->getDefinition('qimnet.update_tracker.cache_repositories');
        foreach($container->findTaggedServiceIds('qimnet.update_tracker.cache_repository') as $servideId=>$attributes) {
            $definition->addMethodCall('addRepository', array($attributes[0]['alias'], new Reference($servideId)));
        }
    }
}

?>
