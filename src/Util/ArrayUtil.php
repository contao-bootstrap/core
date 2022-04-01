<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Util;

use function is_array;
use function is_numeric;

/**
 * Class ArrayUtil provides a safe array merge method.
 *
 * It does not create duplicates as the php array_merge method does.
 */
final class ArrayUtil
{
    /**
     * Merge two arrays recursively.
     *
     * @param array<array-key,mixed> $array1 First array.
     * @param array<array-key,mixed> $array2 Second array.
     *
     * @return array<array-key,mixed>
     *
     * @noinspection PhpPluralMixedCanBeReplacedWithArrayInspection
     */
    public static function merge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::merge($merged[$key], $value);
            } elseif (is_numeric($key)) {
                $merged[] = $value;
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
