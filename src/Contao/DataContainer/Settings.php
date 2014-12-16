<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2014 netzmacht creative David Molineus
 */

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
