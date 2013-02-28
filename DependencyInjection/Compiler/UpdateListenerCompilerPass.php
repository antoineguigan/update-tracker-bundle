<?php
namespace Qimnet\UpdateTrackerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UpdateListenerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('qimnet.update_tracker.repository')) return;
        
        $definition = $container->getDefinition('qimnet.update_tracker.repository');
        foreach($container->findTaggedServiceIds('qimnet.update_tracker.listener') as $servideId=>$attributes) {
            $definition->addMethodCall('addEventListener', array(new Reference($servideId)));
        }
    }
}

?>
