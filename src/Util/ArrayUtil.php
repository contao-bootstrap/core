<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Patrick Landolt <patrick.landolt@artack.ch>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Util;

/**
 * Class ArrayUtil provides a safe array merge method.
 *
 * It does not creates duplicates as the php array_merge method does.
 *
 * @package ContaoBootstrap\Core\Util
 */
final class ArrayUtil
{
    /**
     * Merge two arrays recursively.
     *
     * @param array $array1 First array.
     * @param array $array2 Second array.
     * @param bool $replaceNumericArrays Flag wheter numeric arrays should be merged (added) or replaced.
     *
     * @return array
     */
    public static function merge(array $array1, array $array2, $replaceNumericArrays = false): array
    {
        $merged = $array1;

        // special case for flat arrays
        if ($replaceNumericArrays && self::isNumericArray($array2)) {
            return $array2;
        }

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]) && (!$replaceNumericArrays || !self::isNumericArray($value))) {
                $merged[$key] = static::merge($merged[$key], $value, $replaceNumericArrays);
            } elseif (!is_numeric($key) || (is_array($value) && $replaceNumericArrays && self::isNumericArray($value))) {
                $merged[$key] = $value;
            } else {
                $merged[] = $value;
            }
        }

        return $merged;
    }

    /**
     * Check wheter an array has only numeric keys.
     *
     * @param array $array The array to check for only numeric keys.
     *
     * @return bool
     */
    public static function isNumericArray(array $array): bool
    {
        $nonIntegerKeys = array_filter(array_keys($array), function($key) {
            return !is_int($key);
        });

        return 0 === count($nonIntegerKeys);
    }
}
