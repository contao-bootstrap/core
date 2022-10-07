<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\Environment;

use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment\ThemeContext;
use PhpSpec\ObjectBehavior;

/** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
class ThemeContextSpec extends ObjectBehavior
{
    public const THEME_ID = 4;

    public function let(): void
    {
        $this->beConstructedThrough('forTheme', [self::THEME_ID]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ThemeContext::class);
    }

    public function it_is_a_context(): void
    {
        $this->shouldImplement(Context::class);
    }

    public function it_provides_theme_id(): void
    {
        $this->getThemeId()->shouldReturn(self::THEME_ID);
    }

    public function it_supports_application_context(): void
    {
        $this->supports(new ApplicationContext())->shouldReturn(true);
    }

    public function it_supports_same_theme_context(): void
    {
        $context = ThemeContext::forTheme(self::THEME_ID);
        $this->supports($context)->shouldReturn(true);
    }

    public function it_does_not_support_other_theme_context(): void
    {
        $context = ThemeContext::forTheme(5);
        $this->supports($context)->shouldReturn(false);
    }

    public function it_serializes_to_string(): void
    {
        $this->toString()->shouldReturn('theme:' . self::THEME_ID);
    }
}
