<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

Bit3\Contao\MetaPalettes\MetaPalettes::appendTo('tl_settings', array(
    'bootstrap' => array(':hide', 'bootstrapIconSet')
));

$GLOBALS['TL_DCA']['tl_settings']['fields']['bootstrapIconSet'] = array
(
    'label'                 => &$GLOBALS['TL_LANG']['tl_settings']['bootstrapIconSet'],
    'inputType'             => 'select',
    'options_callback'      => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\Settings', 'getIconSets'),
    'eval'                  => array(
        //'includeBlankOption' => true,
        'tl_class'           => 'clr w50',
    )
);
