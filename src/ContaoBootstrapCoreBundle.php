<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ContaoBootstrapCoreExtension;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoBootstrapCoreBundle extends Bundle
{
    public function getContainerExtension(): Extension
    {
        return new ContaoBootstrapCoreExtension();
    }
}
