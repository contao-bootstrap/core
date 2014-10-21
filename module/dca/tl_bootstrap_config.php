<?php

$GLOBALS['TL_DCA']['tl_bootstrap_config'] = array
(
    'config' => array
    (
        'dataContainer' => 'Table',
        'ptable'        => 'tl_theme',
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),

    'list' => array
    (
        'sorting' => array
        (
            'mode' => '1',
            'fields' => array('type'),
        ),
        'label' => array(
            'fields' => array('name', 'type'),
            'format' => '%s <span class="tl_gray">[%s]</span>',
        ),
        'operations' => array(
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
        ),

    ),

    'palettes' => array
    (
        '__selector__' => array('type', 'remove')
    ),

    'metapalettes' => array
    (
        'default' => array(
            'type'      => array('type'),
            'config'    => array(),
            'published' => array('published'),
        ),

        'remove extends default' => array
        (
            '+type' => array('name', 'remove'),
        ),

        'icons_set extends remove' => array
        (
            '+config' => array('icons_path', 'icons_template', 'icons_default', 'icons_source'),
        )
    ),

    'metasubselectpalettes' => array
    (
        'icons_source' => array(
            'files' => array('icons_files'),
            'paths' => array('icons_paths'),
        )
    ),

    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

        'sorting' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

        'type' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['type'],
            'inputType'        => 'select',
            'options_callback' => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig', 'getTypes'),
            'eval'             => array(
                'tl_class' => 'w50',
            ),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),

        'name' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['name'],
            'inputType' => 'text',
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),

        'remove' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['remove'],
            'inputType' => 'checkbox',
            'eval'      => array(
                'tl_class' => 'clr w50',
                'submitOnChange' => true,
            ),
            'sql'                     => "char(1) NOT NULL default ''"
        ),

        'icons_path' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_path'],
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
                'mandatory' => true,
            ),
            'sql'                     => "varchar(64) NOT NULL default ''",
            'save_callback' => array(
                array('Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig', 'guardFileExists')
            )
        ),

        'icons_template' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_path'],
            'inputType' => 'text',
            'eval' => array(
                'tl_class' => 'w50',
            ),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),

        'icons_default' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_default'],
            'inputType' => 'checkbox',
            'eval' => array(
                'tl_class' => 'm12 w50',
            ),
            'sql'                     => "char(1) NOT NULL default ''"
        ),

        'icons_source' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['remove'],
            'inputType' => 'select',
            'options'   => array('files', 'paths'),
            'eval'      => array(
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
            ),
            'sql'                     => "char(1) NOT NULL default ''"
        ),

        'icons_paths' => array
        (

        ),

        'icons_files' => array
        (

        ),
    )

);