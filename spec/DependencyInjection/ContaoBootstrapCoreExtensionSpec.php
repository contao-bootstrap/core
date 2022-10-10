<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\DependencyInjection;

use ContaoBootstrap\Core\DependencyInjection\Configuration;
use ContaoBootstrap\Core\DependencyInjection\ContaoBootstrapCoreExtension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
final class ContaoBootstrapCoreExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldBeAnInstanceOf(ContaoBootstrapCoreExtension::class);
    }

    public function it_has_custom_alias(): void
    {
        $this->getAlias()->shouldBe('contao_bootstrap');
    }

    public function it_creates_configuration(ContainerBuilder $builder): void
    {
        $this->getConfiguration([], $builder)->shouldBeAnInstanceOf(Configuration::class);
    }

    public function it_loads_default_configuration(ContainerBuilder $container): void
    {
        $container->fileExists(Argument::any())->willReturn(true);
        $container->removeBindings(Argument::cetera())->willReturn();
        $container->setDefinition(Argument::cetera())->willReturn();

        $container
            ->setParameter('contao_bootstrap.config', [])
            ->shouldBeCalled();

        $container->setParameter('contao_bootstrap.backend.css', [])->shouldBeCalled();

        $this->load([], $container);
    }

    public function it_loads_custom_configuration(ContainerBuilder $container): void
    {
        $container->fileExists(Argument::any())->willReturn(true);
        $container->removeBindings(Argument::cetera())->willReturn();
        $container->setDefinition(Argument::cetera())->willReturn();

        $container
            ->setParameter(
                'contao_bootstrap.config',
                [
                    'layout' => [
                        'metapalette' => [
                            '+title' => ['layoutType'],
                        ],
                        'metasubselectpalettes' => [],
                    ],
                ],
            )
            ->shouldBeCalled();

        $container->setParameter('contao_bootstrap.backend.css', ['style.css'])->shouldBeCalled();

        $this->load(
            [
                'contao_bootstrap' => [
                    'config' => [
                        'layout' => [
                            'metapalette' => [
                                '+title' => ['layoutType'],
                            ],
                            'metasubselectpalettes' => [],
                        ],
                    ],
                    'backend' => [
                        'css' => ['style.css'],
                    ],
                ],
            ],
            $container,
        );
    }
}
