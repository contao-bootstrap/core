<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Contao\DataContainer;

use ContaoBootstrap\Core\Bootstrap;

/**
 * Class Settings is used in tl_settings.
 *
 * @package ContaoBootstrap\Core\Contao\DataContainer
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
        $options = array();
        $sets    = Bootstrap::getConfigVar('icons.sets', array());

        foreach ($sets as $name => $config) {
            if (isset($config['label'])) {
                $options[$name] = $config['label'];
            } else {
                $options[$name] = $name;
            }
        }

        return $options;
    }
}
