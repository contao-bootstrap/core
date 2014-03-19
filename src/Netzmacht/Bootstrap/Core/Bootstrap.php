<?php

namespace Netzmacht\Bootstrap\Core;

use Netzmacht\Bootstrap\Core\Helper;

/**
 * Class Bootstrap provides access to core bootstrap component module
 *
 * @package Netzmacht\Bootstrap
 */
class Bootstrap
{

	/**
	 * Returns true if Bootstrap is enabled
	 *
	 * @return bool
	 */
	public static function isEnabled()
	{
		return Environment::getInstance()->isEnabled();
	}


	/**
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	public static function getConfigVar($key, $default=null)
	{
		return static::getConfig()->get($key, $default);
	}


	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public static function setConfigVar($key, $value)
	{
		static::getConfig()->set($key, $value);
	}


	/**
	 * Returns the bootstrap configuration
	 *
	 * @return Config
	 */
	public static function getConfig()
	{
		return Environment::getInstance()->getConfig();
	}


	/**
	 * Generates an icon
	 *
	 * @param $icon
	 * @param null $class
	 * @return string
	 */
	public static function getIcon($icon, $class=null)
	{
		return static::getIcons()->generateIcon($icon, $class);
	}


	/**
	 * @return Helper\Icons
	 */
	public static function getIcons()
	{
		return Environment::getInstance()->getIcons();
	}


	/**
	 * @param $file
	 * @param null $identifier
	 */
	public static function addComponentCss($file, $identifier=null)
	{
		if(static::getConfigVar('assets.use-component-css')) {
			if($identifier) {
				$GLOBALS['TL_CSS'][$identifier] = $file;
			}
			else {
				$GLOBALS['TL_CSS'][] = $file;
			}
		}
	}

	/**
	 * @param $file
	 * @param null $identifier
	 */
	public static function addComponentJs($file, $identifier=null)
	{
		if(static::getConfigVar('assets.use-component-js')) {
			if($identifier) {
				$GLOBALS['TL_JAVASCRIPT'][$identifier] = $file;
			}
			else {
				$GLOBALS['TL_JAVASCRIPT'][] = $file;
			}
		}
	}

} 