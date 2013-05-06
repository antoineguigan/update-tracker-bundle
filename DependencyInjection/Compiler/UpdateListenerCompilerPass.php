<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Qimnet\UpdateTrackerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UpdateListenerCompilerPass implements CompilerPassInterface
{
    /**
     * Adds listeners to the "qimnet.update_tracker.repository service".
     *
     * Listener services should be tagged with "qimnet.update_tracker.listener"
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('qimnet.update_tracker.repository')) return;

        $definition = $container->getDefinition('qimnet.update_tracker.repository');
        foreach ($container->findTaggedServiceIds('qimnet.update_tracker.listener') as $servideId=>$attributes) {
            $definition->addMethodCall('addEventListener', array(new Reference($servideId)));
        }
    }
}
