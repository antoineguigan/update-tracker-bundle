<?php

namespace Qimnet\UpdateTrackerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class QimnetUpdateTrackerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DependencyInjection\Compiler\UpdateListenerCompilerPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\CacheRepositoryCompilerPass());
    }
}
