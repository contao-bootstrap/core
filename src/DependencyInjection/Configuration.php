<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('contao_bootstrap_core');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->variableNode('config')
                    ->info('Customize the bootstrap configuration');

        return $treeBuilder;
    }
}
