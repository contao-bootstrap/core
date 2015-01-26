<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core;

/**
 * Class Bootstrap provides access to core bootstrap component module.
 *
 * @package Netzmacht\Bootstrap
 */
class Bootstrap
{
    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private static $environment;

    /**
     * Returns true if Bootstrap is enabled.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return static::getEnvironment()->isEnabled();
    }

    /**
     * Get config value.
     *
     * @param string $key     Name of config key.
     * @param mixed  $default Default value being used if config key does not exists.
     *
     * @return mixed
     */
    public static function getConfigVar($key, $default = null)
    {
        return static::getConfig()->get($key, $default);
    }

    /**
     * Set a config var.
     *
     * @param string $key   Name of config key.
     * @param mixed  $value Value of the config key.
     *
     * @return void
     */
    public static function setConfigVar($key, $value)
    {
        static::getConfig()->set($key, $value);
    }

    /**
     * Returns the bootstrap configuration.
     *
     * @return Config
     */
    public static function getConfig()
    {
        return static::getEnvironment()->getConfig();
    }

    /**
     * Generates an icon.
     *
     * @param string      $icon  Icon name.
     * @param string|null $class Additional icon class.
     *
     * @return string
     */
    public static function generateIcon($icon, $class = null)
    {
        return static::getIconSet()->generateIcon($icon, $class);
    }

    /**
     * Get the icon set.
     *
     * @return IconSet
     */
    public static function getIconSet()
    {
        return static::getEnvironment()->getIconSet();
    }

    /**
     * Get the page layout.
     *
     * @return \LayoutModel|null
     */
    public static function getPageLayout()
    {
        return static::getEnvironment()->getLayout();
    }

    /**
     * Get the environment.
     *
     * @return Environment
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function getEnvironment()
    {
        if (!self::$environment) {
            self::$environment = $GLOBALS['container']['bootstrap.environment'];
        }

        return self::$environment;
    }
}
