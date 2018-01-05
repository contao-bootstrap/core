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

// define default bootstrap palette
$GLOBALS['TL_DCA']['tl_content']['metapalettes']['_bootstrap_default_'] = array
(
    'type'      => array('type', 'headline'),
    'link'      => array(),
    'config'    => array(),
    'protected' => array(':hide', 'protected'),
    'expert'    => array(':hide', 'guests', 'cssID', 'space'),
    'invisible' => array(':hide', 'invisible', 'start', 'stop'),
);

// bootstrap empty palettes
$GLOBALS['TL_DCA']['tl_content']['metapalettes']['_bootstrap_empty_'] = array
(
    'type' => array('type'),
);

// Bootstrap parent palette.
$GLOBALS['TL_DCA']['tl_content']['metapalettes']['bootstrap_parent extends _bootstrap_empty_'] = array
(
    '+type' => array('bootstrap_parentId'),
);

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_dataAttributes'] = array
(
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => array(
        'tl_class'     => 'clr',
        'columnFields' => array
        (
            'name'  => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes_name'],
                'exclude'   => true,
                'inputType' => 'select',
                'options_callback' => ['contao_bootstrap.core.listener.content_dca', 'getDataAttributes'],
                'reference' => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_buttons_types'],
                'eval'      => array('style' => 'width: 145px;', 'includeBlankOption' => true, 'chosen' => true),
            ),
            'value' => array
            (
                'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes_value'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => array('style' => 'width: 160px', 'allowHtml' => true),
            ),
        )
    ),
    'sql'       => "blob NULL"
);
