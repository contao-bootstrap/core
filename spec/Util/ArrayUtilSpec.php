<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\Util;

use ContaoBootstrap\Core\Util\ArrayUtil;
use PhpSpec\ObjectBehavior;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class ArrayUtilSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ArrayUtil::class);
    }

    public function it_merges_duplicate_string_keys_distinct(): void
    {
        $this->merge(
            ['foo' => 'bar'],
            ['foo' => 'baz']
        )->shouldReturn(['foo' => 'baz']);
    }

    public function it_append_numeric_keys(): void
    {
        $this->merge(['a', 'b'], ['c'])->shouldReturn(['a', 'b', 'c']);
    }
}
