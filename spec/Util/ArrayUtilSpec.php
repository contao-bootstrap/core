<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @author    Patrick Landolt <patrick.landolt@artack.ch>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace spec\ContaoBootstrap\Core\Util;

use ContaoBootstrap\Core\Util\ArrayUtil;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayUtilSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArrayUtil::class);
    }

    function it_merges_duplicate_string_keys_distinct()
    {
        $this->merge(
            [
                'foo' => 'bar',
            ],
            [
                'foo' => 'baz',
            ]
        )->shouldReturn(['foo' => 'baz']);
        $this->merge(
            [
                'foo' => 'bar',
                'a' => 'b',
            ],
            [
                'foo' => 'baz',
                'c' => 'd',
            ]
        )->shouldReturn(['foo' => 'baz', 'a' => 'b', 'c' => 'd']);

        $this->merge(
            [
                'foo' => 'bar',
                'fooBar' => [
                    'foo' => 'bar',
                    'fooBar' => 'baz',
                ]
            ],
            [
                'foo' => 'baz',
                'fooBar' => [
                    'foo' => 'a',
                ]
            ]
        )->shouldReturn(['foo' => 'baz', 'fooBar' => ['foo' => 'a', 'fooBar' => 'baz']]);
    }

    function it_append_numeric_keys()
    {
        $this->merge(['a', 'b'], ['c'])->shouldReturn(['a', 'b', 'c']); // no recursion
        $this->merge(['a' => ['b'], 'c'], ['a' => ['d']])->shouldReturn(['a' => ['b', 'd'], 'c']); // with recursion
    }

    function it_replace_numeric_keys()
    {
        $this->merge(['a', 'b'], ['c'], true)->shouldReturn(['c']); // no recursion
        $this->merge(['a' => ['b'], 'c'], ['a' => ['d']], true)->shouldReturn(['a' => ['d'], 'c']); // with recursion
    }
}
