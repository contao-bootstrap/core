<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Config\Model\BootstrapConfigModel;

/**
 * Interface Type describe a config type.
 *
 * @package ContaoBootstrap\Core\Config
 */
interface Type
{
    /**
     * Get the name of the config.
     *
     * @return int
     */
    public function getName();

    /**
     * Build config from model.
     *
     * @param Config               $config Bootstrap config.
     * @param BootstrapConfigModel $model  Bootstrap config model.
     *
     * @return void
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model);

    /**
     * Extract config and build the model.
     *
     * @param string               $key    Bootstrap config key.
     * @param Config               $config Bootstrap config.
     * @param BootstrapConfigModel $model  Bootstrap config model.
     *
     * @return void
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model);

    /**
     * Consider if config has a global scope, means is not limited to a specific theme.
     *
     * @return bool
     */
    public function hasGlobalScope();

    /**
     * Consider if type is a collection of multiple elements.
     *
     * @return bool
     */
    public function isMultiple();

    /**
     * Consider if name can be edited.
     *
     * @return bool
     */
    public function isNameEditable();

    /**
     * Get config path.
     *
     * @return string
     */
    public function getPath();
}
