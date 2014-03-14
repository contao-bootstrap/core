<?php

\MetaPalettes::appendTo('tl_settings', array(
	'bootstrap' => array(':hide', 'bootstrapEnabled', 'bootstrapIconSet')
));


$GLOBALS['TL_DCA']['tl_settings']['fields']['bootstrapEnabled'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_config']['bootstrapEnabled'],
	'inputType'             => 'checkbox',
	'eval'                  => array(
		//'includeBlankOption' => true,
		'tl_class'           => '',
	)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['bootstrapIconSet'] = array
(
	'label'                 => &$GLOBALS['TL_LANG']['tl_config']['bootstrapIconSet'],
	'inputType'             => 'select',
	'options_callback'      => array('Netzmacht\Bootstrap\Contao\DataContainer\Config', 'getIconSets'),
	'eval'                  => array(
		//'includeBlankOption' => true,
		'tl_class'           => 'clr w50',
	)
);