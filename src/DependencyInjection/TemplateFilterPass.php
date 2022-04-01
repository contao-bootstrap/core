<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use function array_keys;

final class TemplateFilterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->processPreRenderFilters($container);
        $this->processPostRenderFilters($container);
    }

    /**
     * Process tagged pre render filters.
     *
     * @param ContainerBuilder $container Container builder.
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
     */
    private function registerTaggedServices(
        ContainerBuilder $container,
        string $definitionId,
        string $tagName,
        $argumentIndex = 0
    ): bool {
        if (! $container->has($definitionId)) {
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
