<?php

namespace Netzmacht\Bootstrap\Core;

/**
 * Class Bootstrap provides access to core bootstrap component module
 *
 * @package Netzmacht\Bootstrap
 */
class Bootstrap
{
    /**
     * @var Environment
     */
    private static $environment;

    /**
     * Returns true if Bootstrap is enabled
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return static::getEnvironment()->isEnabled();
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function getConfigVar($key, $default = null)
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
        return static::getEnvironment()->getConfig();
    }

    /**
     * Generates an icon
     *
     * @param $icon
     * @param null $class
     * @return string
     */
    public static function generateIcon($icon, $class = null)
    {
        return static::getIconSet()->generateIcon($icon, $class);
    }

    /**
     * @return IconSet
     */
    public static function getIconSet()
    {
        return static::getEnvironment()->getIconSet();
    }

    /**
     * @return \LayoutModel|null
     */
    public static function getPageLayout()
    {
        return static::getEnvironment()->getLayout();
    }

    /**
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
