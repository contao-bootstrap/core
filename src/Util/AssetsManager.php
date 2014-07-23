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


class AssetsManager
{
	/**
	 * @param $stylesheets
	 * @param null $prefix
	 */
	public static function addStylesheets($stylesheets, $prefix=null)
	{
		foreach((array) $stylesheets as $name => $file) {
			if($prefix) {
				$name = $prefix . '-' . $name;
			}

			$GLOBALS['TL_CSS'][$name] = $file;
		}
	}

	/**
	 * @param $javascripts
	 * @param null $prefix
	 */
	public static function addJavascripts($javascripts, $prefix=null)
	{
		foreach((array) $javascripts as $name => $file) {
			if($prefix) {
				$name = $prefix . '-' . $name;
			}

			$GLOBALS['TL_JAVASCRIPT'][$name] = $file;
		}
	}

} 