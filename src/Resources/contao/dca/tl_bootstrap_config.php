<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

\Controller::loadLanguageFile('default');

$GLOBALS['TL_DCA']['tl_bootstrap_config'] = array
(
    'config'                => array
    (
        'dataContainer' => 'Table',
        'ptable'        => 'tl_theme',
        'oncreate_callback' => array(
            array(
                'ContaoBootstrap\Core\DataContainer\BootstrapConfig',
                'addOverrideInformation'
            )
        ),
        'palettes_callback' => array(
            array(
                'ContaoBootstrap\Core\DataContainer\BootstrapConfig',
                'addNameToPalette'
            )
        ),
        'sql'           => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),
    'list'                  => array
    (
        'sorting'    => array
        (
            'mode'   => '1',
            'fields' => array('type'),
            'panelLayout'             => 'sort,search,filter,limit',
        ),
        'label'      => array(
            'fields'         => array('type'),
            'format'         => '%s',
            'label_callback' => array(
                'ContaoBootstrap\Core\DataContainer\BootstrapConfig',
                'generateLabel'
            ),
        ),
        'global_operations' => array(
            'override'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['override'],
                'href'  => sprintf('act=create&amp;mode=2&amp;pid=%s&override=1', \Input::get('id')),
                'class'  => 'header_theme_import'
            ),
        ),
        'operations' => array(
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array(
                    'ContaoBootstrap\Core\DataContainer\BootstrapConfig',
                    'toggleIcon'
                ),
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ),
        ),
    ),
    'palettes'              => array
    (
        '__selector__' => array('type', 'remove')
    ),
    'metapalettes'          => array
    (
        'default'                  => array(
            'type'        => array('type'),
            'description' => array(':hide','description'),
            'config'      => array(),
            'published'   => array('published'),
        ),
    ),
    'fields'                => array
    (
        'id'             => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid'            => array
        (
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'foreignKey' => 'tl_theme.name',
            'relation'   => array('type' => 'belongsTo', 'load' => 'lazy')
        ),
        'tstamp'         => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting'        => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'override'        => array
        (
            'sql' => "char(1) NOT NULL default ''"
        ),
        'type'           => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['type'],
            'inputType'        => 'select',
            'filter'           => true,
            'options_callback' => array('ContaoBootstrap\Core\DataContainer\BootstrapConfig', 'getTypes'),
            'reference'        => &$GLOBALS['TL_LANG']['bootstrap_config_type'],
            'save_callback'    => array(
                array('ContaoBootstrap\Core\DataContainer\BootstrapConfig', 'importFromConfig'),
            ),
            'load_callback'    => array(
                array('ContaoBootstrap\Core\DataContainer\BootstrapConfig', 'warnByGlobalConfig'),
            ),
            'eval'             => array(
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'mandatory'          => true,
            ),
            'sql'              => "varchar(32) NOT NULL default ''"
        ),
        'name'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['name'],
            'inputType' => 'text',
            'options_callback' => array('ContaoBootstrap\Core\DataContainer\BootstrapConfig', 'getNames'),
            'eval'      => array(
                'tl_class'           => 'w50',
                'mandatory'          => true,
                'includeBlankOption' => true,
                'submitOnChange'     => true,
            ),
            'sql'       => "varchar(64) NOT NULL default ''",
        ),
        'description'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['description'],
            'inputType' => 'text',
            'eval'      => array(
                'tl_class'  => 'clr long',
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'remove'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['remove'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class'       => 'clr w50',
                'submitOnChange' => true,
            ),
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'global'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['global'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class'       => 'clr w50',
                'submitOnChange' => true,
            ),
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'published'      => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['published'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class'       => 'clr w50',
                'submitOnChange' => true,
            ),
            'sql'       => "char(1) NOT NULL default ''"
        ),
    )
);
