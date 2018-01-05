<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace spec\ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Config\ArrayConfig;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayConfigSpec extends ObjectBehavior
{
    private $config = [
        'foo' => [
            'bar' => 'baz'
        ]
    ];

    function let()
    {
        $this->beConstructedWith($this->config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArrayConfig::class);
    }

    function it_implements_config()
    {
        $this->shouldImplement(Config::class);
    }

    function it_gets_config_by_string_key()
    {
        $this->get('foo.bar')->shouldReturn('baz');
    }

    function it_gets_config_by_array_key()
    {
        $this->get(['foo', 'bar'])->shouldReturn('baz');
    }

    function it_returns_default_value_if_config_not_exist()
    {
        $this->has('bar')->shouldReturn(false);
        $this->get('bar')->shouldReturn(null);
        $this->get('bar', 'baz')->shouldReturn('baz');
    }

    function it_checks_if_config_exists()
    {
        $this->has('foo.bar')->shouldReturn(true);
        $this->has(['foo', 'bar'])->shouldReturn(true);

        $this->has('foo.baz')->shouldReturn(false);
        $this->has(['foo', 'baz'])->shouldReturn(false);
    }

    function it_is_imutable_when_merging_config()
    {
        $this->has('bar')->shouldReturn(false);
        $config = $this->merge(['bar' => true]);
        $this->has('bar')->shouldReturn(false);

        $config->has('bar')->shouldReturn(true);
    }
}
