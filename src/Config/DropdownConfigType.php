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


use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;

class DropdownConfigType implements ConfigType
{
    /**
     * @param Config $config
     * @param BootstrapConfigModel $model
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        // TODO: Implement buildConfig() method.
    }

    /**
     * @param $key
     * @param Config $config
     * @param BootstrapConfigModel $model
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model)
    {
        $model->dropdown_toggle = $config->get($key . '.toggle');
    }

    /**
     * @return bool
     */
    public function hasGlobalScope()
    {
        // TODO: Implement hasGlobalScope() method.
    }

    /**
     * @return bool
     */
    public function isMultiple()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'dropdown';
    }

} 