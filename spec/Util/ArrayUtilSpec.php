<?php

declare(strict_types=1);

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
