<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core;

use ContaoBootstrap\Core\DependencyInjection\ContaoBootstrapCoreExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
final class ContaoBootstrapCoreBundleSpec extends ObjectBehavior
{
    public function it_is_a_bundle(): void
    {
        $this->shouldBeAnInstanceOf(Bundle::class);
    }

    public function it_has_a_custom_extension(): void
    {
        $this->getContainerExtension()->shouldBeAnInstanceOf(ContaoBootstrapCoreExtension::class);
    }
}
