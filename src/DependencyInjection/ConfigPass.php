<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection;

use ContaoBootstrap\Core\Util\ArrayUtil;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;

use function array_keys;
use function dirname;
use function file_exists;
use function file_get_contents;

final class ConfigPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $this->loadConfigFromBundles($container);
        $this->setConfigTypesArgument($container);
    }

    /**
     * Load configuration from bundle files.
     *
     * @param ContainerBuilder $container Container builder.
     */
    private function loadConfigFromBundles(ContainerBuilder $container): void
    {
        $config = [];

        /** @psalm-suppress UndefinedDocblockClass - UnitEnum is PHP 8 onlyFix  */
        foreach ((array) $container->getParameter('kernel.bundles') as $bundleClass) {
            $refClass  = new ReflectionClass($bundleClass);
            $bundleDir = dirname($refClass->getFileName());

            $configFile = $bundleDir . '/Resources/config/contao_bootstrap.yaml';
            if (file_exists($configFile)) {
                $config = ArrayUtil::merge($config, Yaml::parse(file_get_contents($configFile)));
                continue;
            }

            $configFile = $bundleDir . '/Resources/config/contao_bootstrap.yml';
            if (! file_exists($configFile)) {
                continue;
            }

            $config = ArrayUtil::merge($config, Yaml::parse(file_get_contents($configFile)));
        }

        $container->setParameter('contao_bootstrap.config', $config);
    }

    /**
     * Set the config types arguments.
     *
     * @param ContainerBuilder $container Container builder.
     */
    private function setConfigTypesArgument(ContainerBuilder $container): void
    {
        if (! $container->has('contao_bootstrap.config.type_manager')) {
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
