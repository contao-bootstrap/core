<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Config\Type;

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Config\Type;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;

/**
 * Class DropdownType is used for dropdown informations.
 *
 * @package Netzmacht\Bootstrap\Core\Config\Type
 */
class DropdownType implements Type
{
    /**
     * {@inheritdoc}
     */
    public function buildConfig(Config $config, BootstrapConfigModel $model)
    {
        $config->set('dropdown.toggle', $model->dropdown_toggle);
        $config->set('dropdown.formless', deserialize($model->dropdown_formless, true));
    }

    /**
     * {@inheritdoc}
     */
    public function extractConfig($key, Config $config, BootstrapConfigModel $model)
    {
        $model->dropdown_toggle   = $config->get($key . '.toggle');
        $model->dropdown_formless = $config->get($key . '.formless');
    }

    /**
     * {@inheritdoc}
     */
    public function hasGlobalScope()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiple()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return 'dropdown';
    }

    /**
     * {@inheritdoc}
     */
    public function isNameEditable()
    {
        return false;
    }
}
