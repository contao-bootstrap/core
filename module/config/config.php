<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'execute');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'parse');

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\AssetsCollector';

// Add
if(TL_MODE == 'BE') {
	$GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|all|static';
}

// Bootstrap config
$GLOBALS['BOOTSTRAP']['dropdown']['toggle']     = '<b class="caret"></b>';
$GLOBALS['BOOTSTRAP']['dropdown']['formless'][] = 'mod_quicklink';