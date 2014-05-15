<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem']['bootstrap-core']      = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags']['bootstrap-core']     = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate']['bootstrap-core'] = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'rewriteCssClasses');

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';

// Add
if(TL_MODE == 'BE') {
	$GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|all|static';
}

require_once TL_ROOT . '/system/modules/bootstrap-core/config/bootstrap.php';
