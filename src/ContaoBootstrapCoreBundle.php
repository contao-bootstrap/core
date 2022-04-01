<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ConfigPass;
use ContaoBootstrap\Core\DependencyInjection\TemplateFilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoBootstrapCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ConfigPass());
        $container->addCompilerPass(new TemplateFilterPass());
    }
}
