<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection;

use ContaoBootstrap\Core\ContaoBootstrapComponent;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /** @param list<ContaoBootstrapComponent> $components */
    public function __construct(private readonly array $components = [])
    {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_bootstrap');
        $rootNode    = $treeBuilder->getRootNode();

        $configNode = $rootNode
                ->children()
                    ->arrayNode('backend')
                        ->info('Backend related configuration')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('css')
                                ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode('config')
                        ->info('Customize the bootstrap configuration');

        $configNode
            ->children()
                ->arrayNode('layout')
                    ->info('Layout related configuration')
                    ->children()
                        ->arrayNode('metapalette')
                            ->info('Customize the palette of a bootstrap layout')
                            ->example(['+title' => ['layoutType']])
                            ->normalizeKeys(false)
                            ->arrayPrototype()
                                ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('metasubselectpalettes')
                            ->info('Adjust subselect palettes')
                            ->arrayPrototype()
                                ->arrayPrototype()
                                    ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        foreach ($this->components as $component) {
            $component->addBootstrapConfiguration($configNode);
        }

        return $treeBuilder;
    }
}
