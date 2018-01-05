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

namespace ContaoBootstrap\Core\DependencyInjection;

use ContaoBootstrap\Core\Util\ArrayUtil;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigPass.
 *
 * @package ContaoBootstrap\Core\DependencyInjection
 */
final class ConfigPass implements CompilerPass
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->loadConfigFromBundles($container);
        $this->setConfigTypesArgument($container);
    }

    /**
     * Load configuration from bundle files.
     *
     * @param ContainerBuilder $container Container builder.
     *
     * @return void
     */
    private function loadConfigFromBundles(ContainerBuilder $container)
    {
        $config = [];

        foreach ($container->getParameter('kernel.bundles') as $bundleClass) {
            $refClass   = new \ReflectionClass($bundleClass);
            $bundleDir  = dirname($refClass->getFileName());
            $configFile = $bundleDir . '/Resources/config/contao_bootstrap.yml';

            if (file_exists($configFile)) {
                $config = ArrayUtil::merge($config, Yaml::parse(file_get_contents($configFile)));
            }
        }

        $container->setParameter('contao_bootstrap.config', $config);
    }

    /**
     * Set the config types arguments.
     *
     * @param ContainerBuilder $container Container builder.
     *
     * @return void
     */
    private function setConfigTypesArgument(ContainerBuilder $container): void
    {
        if (!$container->has('contao_bootstrap.config.type_manager')) {
            return;
        }

        $definition       = $container->findDefinition('contao_bootstrap.config.type_manager');
        $taggedServiceIds = $container->findTaggedServiceIds('contao_bootstrap.config.type');
        $services         = (array) $definition->getArgument(1);

        foreach (array_keys($taggedServiceIds) as $serviceId) {
            $services[] = new Reference($serviceId);
        }

        $definition->replaceArgument(1, $services);
    }
}
