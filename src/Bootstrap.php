<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core;

/**
 * Class Bootstrap provides access to core bootstrap component module.
 *
 * @package ContaoBootstrap
 * @deprecated
 */
class Bootstrap
{
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
        return \Controller::getContainer()->get('contao_bootstrap.environment');
    }
}
