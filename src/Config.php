<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core;

/**
 * Interface Config.
 *
 * @package ContaoBootstrap\Core\Config
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
     * Consider if config param exists.
     *
     * @param string $key Name of the config param.
     *
     * @return bool
     */
    public function has($key);

    /**
     * Merge new configuration values and return a new instance of Config.
     *
     * @param array $config Config values.
     *
     * @return static
     */
    public function merge(array $config);
}
