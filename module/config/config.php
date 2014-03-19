<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem']['bootstrap-core']  = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags']['bootstrap-core'] = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');


// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';


// Bootstrap config
$GLOBALS['BOOTSTRAP']['dropdown'] = array
(
	// element which is used as download toggler
	'toggle' => '<b class="caret"></b>',

	'formless' => array
	(
		'mod_quicklink',
		/* 'mod_quicknav' */
	),
);


// Add
if(TL_MODE == 'BE') {
	$GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|static';
}