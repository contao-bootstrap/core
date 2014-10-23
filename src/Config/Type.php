<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Config;


use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Config;

interface Type
{
    /**
     * @param Config $config
     * @param BootstrapConfigModel $model
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model);

    /**
     * @param $key
     * @param Config $config
     * @param BootstrapConfigModel $model
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model);

    /**
     * @return bool
     */
    public function hasGlobalScope();

    /**
     * @return bool
     */
    public function isMultiple();

    /**
     * @return string
     */
    public function getPath();
} 