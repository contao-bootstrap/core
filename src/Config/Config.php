<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Config;

/**
 * Class Config.
 *
 * @package ContaoBootstrap
 */
interface Config
{
    /**
     * Get a config value.
     *
     * @param string $key     Name of the config param.
     * @param mixed  $default Default value if config is not set.
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set a config value.
     *
     * @param string $key   Name of the config param.
     * @param mixed  $value The new value.
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * Remove a config param.
     *
     * @param string $key Name of the config param.
     *
     * @return $this
     */
    public function remove($key);

    /**
     * Consider if config param exists.
     *
     * @param string $key Name of the config param.
     *
     * @return bool
     */
    public function has($key);

    /**
     * Merge config values into config.
     *
     * @param array $data New data.
     * @param null  $path Optional sub path where to merge in.
     *
     * @return $this
     */
    public function merge(array $data, $path = null);
}
