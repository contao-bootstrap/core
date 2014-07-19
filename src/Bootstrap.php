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
	public static function generateIcon($icon, $class=null)
	{
		return static::getIconSet()->generateIcon($icon, $class);
	}


	/**
	 * @return \Netzmacht\Bootstrap\Core\IconSet
	 */
	public static function getIconSet()
	{
		return Environment::getInstance()->getIconSet();
	}


	/**
	 * @return \LayoutModel|null
	 */
	public static function getPageLayout()
	{
		return Environment::getInstance()->getLayout();
	}

} 