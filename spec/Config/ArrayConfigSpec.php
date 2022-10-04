<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Config\ArrayConfig;
use PhpSpec\ObjectBehavior;

/** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
class ArrayConfigSpec extends ObjectBehavior
{
    /** @var array<string,mixed> */
    private array $config = [
        'foo' => ['bar' => 'baz'],
    ];

    public function let(): void
    {
        $this->beConstructedWith($this->config);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ArrayConfig::class);
    }

    public function it_implements_config(): void
    {
        $this->shouldImplement(Config::class);
    }

    public function it_gets_config_by_string_key(): void
    {
        $this->get('foo.bar')->shouldReturn('baz');
    }

    public function it_gets_config_by_array_key(): void
    {
        $this->get(['foo', 'bar'])->shouldReturn('baz');
    }

    public function it_returns_default_value_if_config_not_exist(): void
    {
        $this->has('bar')->shouldReturn(false);
        $this->get('bar')->shouldReturn(null);
        $this->get('bar', 'baz')->shouldReturn('baz');
    }

    public function it_checks_if_config_exists(): void
    {
        $this->has('foo.bar')->shouldReturn(true);
        $this->has(['foo', 'bar'])->shouldReturn(true);

        $this->has('foo.baz')->shouldReturn(false);
        $this->has(['foo', 'baz'])->shouldReturn(false);
    }

    public function it_is_imutable_when_merging_config(): void
    {
        $this->has('bar')->shouldReturn(false);
        $config = $this->merge(['bar' => true]);
        $this->has('bar')->shouldReturn(false);

        $config->has('bar')->shouldReturn(true);
    }
}
