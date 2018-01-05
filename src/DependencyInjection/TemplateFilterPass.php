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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TemplateModifierPass.
 *
 * @package ContaoBootstrap\Core\DependencyInjection
 */
final class TemplateFilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processPreRenderFilters($container);
        $this->processPostRenderFilters($container);
    }

    /**
     * Process tagged pre render filters.
     *
     * @param ContainerBuilder $container Container builder.
     *
     * @return void
     */
    private function processPreRenderFilters(ContainerBuilder $container): void
    {
        $this->registerTaggedServices(
            $container,
            'contao_bootstrap.view.template.pre_render_filter',
            'contao_bootstrap.pre_render_filter'
        );
    }

    /**
     * Process tagged post render filters.
     *
     * @param ContainerBuilder $container Container builder.
     *
     * @return void
     */
    private function processPostRenderFilters(ContainerBuilder $container): void
    {
        $this->registerTaggedServices(
            $container,
            'contao_bootstrap.view.template.post_render_filter',
            'contao_bootstrap.post_render_filter'
        );
    }

    /**
     * Register a tagged services to an definition argument. Returns false if service does not exist.
     *
     * @param ContainerBuilder $container     Container builder.
     * @param string           $definitionId  Service definition id.
     * @param string           $tagName       Tag name.
     * @param int              $argumentIndex Index of the argument being replaced.
     *
     * @return bool
     */
    private function registerTaggedServices(
        ContainerBuilder $container,
        string $definitionId,
        string $tagName,
        $argumentIndex = 0
    ): bool {
        if (!$container->has($definitionId)) {
            return false;
        }

        $definition       = $container->findDefinition($definitionId);
        $taggedServiceIds = $container->findTaggedServiceIds($tagName);
        $services         = (array) $definition->getArgument($argumentIndex);

        foreach (array_keys($taggedServiceIds) as $serviceId) {
            $services[] = new Reference($serviceId);
        }

        $definition->replaceArgument(0, $services);

        return true;
    }
}
