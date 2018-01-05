<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_layout']['config']['palettes_callback'][] = array(
    'contao_bootstrap.core.listener.layout_dca',
    'generatePalette'
);

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_layout']['fields']['name']['eval']['tl_class'] .= ' w50';

$GLOBALS['TL_DCA']['tl_layout']['fields']['layoutType'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_layout']['layoutType'],
    'default'   => 'default',
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => array('default', 'bootstrap'),
    'reference' => &$GLOBALS['TL_LANG']['tl_layout']['layoutTypes'],
    'eval'      => array('tl_class' => 'w50', 'submitOnChange' => true, 'helpwizard' => true,),
    'sql'       => "varchar(150) NOT NULL default ''"
);
