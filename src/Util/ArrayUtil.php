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


class ArrayUtil
{
	/**
	 * @param $array1
	 * @param $array2
	 * @param bool $distinct
	 * @return array
	 */
	public static function merge($array1, $array2, $distinct=true)
	{
		if($distinct) {
			return static::mergeDistinct($array1, $array2);
		}

		return array_merge_recursive($array1, $array2);
	}

	/**
	 * @param $array1
	 * @param $array2
	 * @return array
	 */
	private static function mergeDistinct($array1, $array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = static::mergeDistinct($merged[$key], $value);
			}
			else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}

} 