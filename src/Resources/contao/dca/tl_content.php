<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array(
    'ContaoBootstrap\Core\DataContainer\Wrapper', 'enableFixParentPalette',
    'ContaoBootstrap\Core\DataContainer\Content', 'setIconTemplate',
);

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

// wrapper elements
$GLOBALS['TL_DCA']['tl_content']['config']['ondelete_callback'][]     = array(
    'ContaoBootstrap\Core\DataContainer\Wrapper',
    'delete'
);

$GLOBALS['TL_DCA']['tl_content']['fields']['type']['save_callback'][] = array(
    'ContaoBootstrap\Core\DataContainer\Wrapper',
    'save'
);


// fields
$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_parentId'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_parentId'],
    'inputType'               => 'select',
    'options_callback'        => array('ContaoBootstrap\Core\DataContainer\Wrapper', 'getParents'),
    'eval'                    => array(
        'includeBlankOption' => true,
        'mandatory'          => true,
        'doNotCopy'          => true,
    ),
    'sql'                     => "int(10) unsigned NULL",
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
                'options_callback' => ['ContaoBootstrap\Core\DataContainer\Content', 'getDataAttributes'],
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
