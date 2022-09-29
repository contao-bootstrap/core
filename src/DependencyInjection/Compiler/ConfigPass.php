<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection\Compiler;

use ContaoBootstrap\Core\Util\ArrayUtil;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

use function dirname;
use function file_exists;
use function file_get_contents;

final class ConfigPass implements CompilerPass
{
    public function process(ContainerBuilder $container): void
    {
        $config = [];

        /** @psalm-suppress UndefinedDocblockClass - UnitEnum is PHP 8 onlyFix */
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

        /** @psalm-var array<string,mixed> $appConfig */
        $appConfig = $container->getParameter('contao_bootstrap.config.app');
        $config    = ArrayUtil::merge($config, $appConfig);

        $container->setParameter('contao_bootstrap.config', $config);
    }
}
