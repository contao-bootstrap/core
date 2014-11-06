<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Util;

/**
 * Class ArrayUtil provides a safe array merge method.
 *
 * It does not creates duplicates as the php array_merge method does.
 *
 * @package Netzmacht\Bootstrap\Core\Util
 */
class ArrayUtil
{
    /**
     * Merge two arrays recursively.
     *
     * @param array $array1   First array.
     * @param array $array2   Second array.
     * @param bool  $distinct If false php_merge_recursive will be used.
     *
     * @return array
     */
    public static function merge($array1, $array2, $distinct = true)
    {
        if ($distinct) {
            return static::mergeDistinct($array1, $array2);
        }

        return array_merge_recursive($array1, $array2);
    }

    /**
     * Merge two arrays recursively.
     *
     * @param array $array1 First array.
     * @param array $array2 Second array.
     *
     * @return array
     */
    private static function mergeDistinct($array1, $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::mergeDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
