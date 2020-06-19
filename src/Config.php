<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Patrick Landolt <patrick.landolt@artack.ch>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

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
     * @param string|array $key     Name of the config param.
     * @param mixed        $default Default value if config is not set.
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Consider if config param exists.
     *
     * @param string|array $key Name of the config param.
     *
     * @return bool
     */
    public function has($key);

    /**
     * Merge new configuration values and return a new instance of Config.
     *
     * @param array $config Config values.
     * @param bool $replaceNumericArrays Flag wheter numeric arrays should be merged (added) or replaced.
     *
     * @return static
     */
    public function merge(array $config, bool $replaceNumericArrays = false): Config;
}
