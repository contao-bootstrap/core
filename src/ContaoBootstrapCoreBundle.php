<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ContaoBootstrapCoreExtension;
use Override;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ContaoBootstrapCoreBundle extends Bundle
{
    #[Override]
    public function getContainerExtension(): Extension
    {
        return new ContaoBootstrapCoreExtension();
    }
}
