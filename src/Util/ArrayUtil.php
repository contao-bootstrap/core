<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
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
     *
     * @return array
     */
    public static function merge(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = static::merge($merged[$key], $value);
            } elseif (is_numeric($key)) {
                $merged[] = $value;
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
