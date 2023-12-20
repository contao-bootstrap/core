<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection;

use ContaoBootstrap\Core\ContaoBootstrapComponent;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class ContaoBootstrapCoreExtension extends Extension
{
    /** @var list<ContaoBootstrapComponent> */
    private array $components = [];

    public function getAlias(): string
    {
        return 'contao_bootstrap';
    }

    public function addComponent(ContaoBootstrapComponent $component): void
    {
        $this->components[] = $component;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration($this->components);
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config'),
        );

        $config = $this->processConfiguration(new Configuration($this->components), $configs);
        $container->setParameter('contao_bootstrap.config', $config['config'] ?? []);
        $container->setParameter('contao_bootstrap.backend.css', $config['backend']['css']);

        $loader->load('services.yaml');
        $loader->load('listeners.yaml');
    }
}
