<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeLayout');
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'modify');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'parse');
$GLOBALS['TL_HOOKS']['parseTemplate'][]  = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'addIconStylesheet');
// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\CoreSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\AssetsCollector';

if(TL_MODE == 'BE') {
	// Add backend stylesheet
	// TODO: Check if styles are still required
	$GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|all|static';

	// load stylepicker config
	if(\Input::get('key') == 'stylepicker4ward_import') {
		require TL_ROOT . '/system/modules/bootstrap-core/config/stylepicker.php';
	}
}