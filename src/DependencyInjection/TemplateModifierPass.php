<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TemplateModifierPass.
 *
 * @package ContaoBootstrap\Core\DependencyInjection
 */
class TemplateModifierPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('contao_bootstrap.view.template_modifier')) {
            return;
        }

        $definition       = $container->findDefinition('contao_bootstrap.view.template_modifier');
        $taggedServiceIds = $container->findTaggedServiceIds('contao_bootstrap.view.template_modifier');
        $services         = (array) $definition->getArgument(0);

        foreach (array_keys($taggedServiceIds) as $serviceId) {
            $services[] = new Reference($serviceId);
        }

        $definition->replaceArgument(0, $services);
    }
}
