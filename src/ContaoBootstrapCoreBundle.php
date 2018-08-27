<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ConfigPass;
use ContaoBootstrap\Core\DependencyInjection\TemplateFilterPass;
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
        $container->addCompilerPass(new TemplateFilterPass());
    }
}
