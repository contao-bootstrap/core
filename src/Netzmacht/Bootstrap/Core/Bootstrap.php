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
	 * @param $name
	 * @return mixed
	 */
	public static function getHelper($name)
	{
		return Environment::getInstance()->getHelper($name);
	}

} 