<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
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
                'foo' => 'bar'
            ],
            [
                'foo' => 'baz',
            ]
        )->shouldReturn(['foo' => 'baz']);
    }

    function it_append_numeric_keys()
    {
        $this->merge(['a', 'b'], ['c'])->shouldReturn(['a', 'b', 'c']);
    }
}
