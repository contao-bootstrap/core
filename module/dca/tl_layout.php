<?php

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_layout']['config']['palettes_callback'][]   = array(
	'Netzmacht\Bootstrap\Core\Contao\DataContainer\Layout',
	'generatePalette'
);


/**
 * Fields
 */
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
