<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\DependencyInjection;

use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigPass collects alls contao-bootstrap.yml config files from the bundles-
 *
 * @package Netzmacht\Bootstrap\Core\DependencyInjection
 */
class ConfigPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        /** @var Config $config */
        $config = $container->get('contao_bootstrap.config');

        // Todo: Is there a more perfomant way to collect the data?
        foreach ($container->getParameter('kernel.bundles') as $name => $bundleClass) {
            $refClass   = new \ReflectionClass($bundleClass);
            $bundleDir  = dirname($refClass->getFileName());
            $configFile = $bundleDir . '/Resources/config/contao-bootstrap.yml';

            if (file_exists($configFile)) {
                $config->merge(
                    Yaml::parse(file_get_contents($configFile))
                );
            }
        }

        // Todo: Add an event here?
    }
}
