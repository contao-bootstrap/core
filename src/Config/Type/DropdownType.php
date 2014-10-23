<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Config\Type;

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Config\Type;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;

class DropdownType implements Type
{
    /**
     * @param Config               $config
     * @param BootstrapConfigModel $model
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        $config->set('dropdown.toggle', $model->dropdown_toggle);
        $config->set('dropdown.formless', deserialize($model->dropdown_formless, true));
    }

    /**
     * @param $key
     * @param Config               $config
     * @param BootstrapConfigModel $model
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model)
    {
        $model->dropdown_toggle   = $config->get($key . '.toggle');
        $model->dropdown_formless = $config->get($key . '.formless');
    }

    /**
     * @return bool
     */
    public function hasGlobalScope()
    {
        return false;
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
