<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace spec\ContaoBootstrap\Core\Environment;

use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment\ThemeContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThemeContextSpec extends ObjectBehavior
{
    const THEME_ID = 4;

    function let()
    {
        $this->beConstructedThrough('forTheme', [static::THEME_ID]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ThemeContext::class);
    }

    function it_is_a_context()
    {
        $this->shouldImplement(Context::class);
    }

    function it_provides_theme_id()
    {
        $this->getThemeId()->shouldReturn(self::THEME_ID);
    }

    function it_supports_application_context()
    {
        $this->supports(new ApplicationContext())->shouldReturn(true);
    }

    function it_supports_same_theme_context()
    {
        $context = ThemeContext::forTheme(self::THEME_ID);
        $this->supports($context)->shouldReturn(true);
    }

    function it_does_not_support_other_theme_context()
    {
        $context = ThemeContext::forTheme(5);
        $this->supports($context)->shouldReturn(false);
    }

    function it_serializes_to_string()
    {
        $this->__toString()->shouldReturn('theme:' . self::THEME_ID);
    }
}
