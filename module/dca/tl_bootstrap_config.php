<?php

$GLOBALS['TL_DCA']['tl_bootstrap_config'] = array
(
    'config'                => array
    (
        'dataContainer' => 'Table',
        'ptable'        => 'tl_theme',
        'oncreate_callback' => array(
            array(
                'Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig',
                'addOverrideInformation'
            )
        ),
        'palettes_callback' => array(
            array(
                'Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig',
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
            'fields'         => array('name', 'type'),
            'format'         => '%s <span class="tl_gray">[%s]</span>',
            'group_callback' => array(
                'Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig',
                'formatGroup'
            ),
        ),
        'global_operations' => array(
            'override'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['override'],
                'href'  => sprintf('act=create&amp;mode=2&amp;pid=%s&override=1', \Input::get('id')),
                'icon'  => 'edit.gif'
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
                    'Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig',
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
            'type'      => array('type'),
            'config'    => array(),
            'published' => array('published'),
        ),

        'icons_set extends default' => array
        (
            '+config' => array('icons_path', 'icons_template', 'icons_default', 'icons_source'),
        ),
        'dropdown extends default' => array
        (
            '+config' => array('dropdown_toggle'),
        )
    ),
    'metasubselectpalettes' => array
    (
        'icons_source' => array(
            'files' => array('icons_files'),
            'paths' => array('icons_paths'),
        )
    ),
    'fields'                => array
    (
        'id'             => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid'            => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
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
            'options_callback' => array('Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig', 'getTypes'),
            'reference'        => &$GLOBALS['TL_LANG']['bootstrap_config_types'],
            'save_callback'    => array(
                array('Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig', 'saveGlobalScope'),
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
            'sql'       => "varchar(64) NOT NULL default ''",
            'eval'      => array(
                'tl_class'  => 'w50',
                'mandatory' => true,
            )
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
        'icons_path'     => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_path'],
            'inputType'     => 'text',
            'eval'          => array(
                'tl_class'  => 'w50',
                'mandatory' => true,
            ),
            'sql'           => "varchar(64) NOT NULL default ''",
            'save_callback' => array(
                array('Netzmacht\Bootstrap\Core\Contao\DataContainer\BootstrapConfig', 'guardFileExists')
            )
        ),
        'icons_template' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_template'],
            'inputType' => 'text',
            'eval'      => array(
                'tl_class'  => 'w50',
                'allowHtml' => true,
            ),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'icons_source'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_source'],
            'inputType' => 'select',
            'options'   => array('files', 'paths'),
            'eval'      => array(
                'tl_class'           => 'w50',
                'includeBlankOption' => true,
                'submitOnChange'     => true,
            ),
            'sql'       => "char(5) NOT NULL default ''"
        ),
        'icons_paths'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_paths'],
            'inputType' => 'textarea',
            'eval'      => array(
                'tl_class' => 'clr long',
            ),
            'sql'       => "text NULL"
        ),
        'icons_files'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['icons_files'],
            'inputType' => 'fileTree',
            'eval'      => array(
                'tl_class'   => 'clr',
                'filesOnly'  => true,
                'extensions' => 'css',
                'fieldType'  => 'checkbox',
            ),
            'sql'       => "blob NULL"
        ),
        'dropdown_toggle' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_bootstrap_config']['dropdown_toggle'],
            'inputType' => 'text',
            'eval'      => array(
                'tl_class'  => 'w50',
                'allowHtml' => true,
            ),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
    )

);