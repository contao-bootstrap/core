<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\Environment;

use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment\ThemeContext;
use PhpSpec\ObjectBehavior;

/** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
class ApplicationContextSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ApplicationContext::class);
    }

    public function it_is_a_context(): void
    {
        $this->shouldImplement(Context::class);
    }

    public function it_provides_named_constructor(): void
    {
        $this->create()->shouldHaveType(ApplicationContext::class);
    }

    public function it_supports_application_context(): void
    {
        $this->supports(new ApplicationContext())->shouldReturn(true);
    }

    public function it_does_not_support_theme_context(): void
    {
        $context = ThemeContext::forTheme(4);
        $this->supports($context)->shouldReturn(false);
    }

    public function it_serializes_to_string(): void
    {
        $this->toString()->shouldReturn('application');
    }
}
