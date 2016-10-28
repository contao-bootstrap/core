<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ConfigPass;
use ContaoBootstrap\Core\DependencyInjection\TemplateModifierPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ContaoBootstrapCoreBundle.
 *
 * @package ContaoBootstrap\Core
 */
class ContaoBootstrapCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigPass());
        $container->addCompilerPass(new TemplateModifierPass());
    }
}
