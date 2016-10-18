<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

return array
(
    'config'   => array(
        'types' => array(
            'icons_set' => 'Netzmacht\Bootstrap\Core\Config\Type\IconSetType',
            'dropdown'  => 'Netzmacht\Bootstrap\Core\Config\Type\DropdownType'
        ),
    ),
    'dropdown' => array(
        'toggle'   => '<span class="caret"></span>',
        'formless' => array('mod_quicklink'),
    ),
    'icons'    => array(
        'sets' => array(
            'glyphicons' => array(
                'path'       => 'system/modules/bootstrap-core/config/glyphicons.php',
                'stylesheet' => 'system/modules/bootstrap-core/assets/css/glyphicons.css',
                'template'   => '<span class="glyphicon glyphicon-%s"></span>',
            )
        )
    ),
    'layout'   => array(
        'metapalette' => array(
            '+title' => array('layoutType')
        )
    )
);
