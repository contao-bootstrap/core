<?php

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Netzmacht\Bootstrap\Core\Bootstrap;

/**
 * Class Settings is used in tl_settings.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\DataContainer
 */
class Settings
{
    /**
     * Get all icon set names.
     *
     * @return array
     */
    public function getIconSets()
    {
        $sets = Bootstrap::getConfigVar('icons.sets', array());
        $sets = array_keys($sets);
        sort($sets);

        return $sets;
    }
}
