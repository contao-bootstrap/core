<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeLayout');
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'execute');
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = array('Netzmacht\Bootstrap\Core\Contao\Template\Modifier', 'parse');

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\AssetsCollector';

// Add backend stylesheet
// TODO: Check if styles are still required
if(TL_MODE == 'BE') {
	$GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|all|static';
}

// Dropdown config
$GLOBALS['BOOTSTRAP']['dropdown']['toggle']     = '<b class="caret"></b>';
$GLOBALS['BOOTSTRAP']['dropdown']['formless'][] = 'mod_quicklink';

// icon sets
$GLOBALS['BOOTSTRAP']['icons']['active']             = 'glyphicons';
$GLOBALS['BOOTSTRAP']['icons']['sets']['glyphicons'] = array
(
	'path'       => 'system/modules/bootstrap-core/config/glyphicons.php',
	'stylesheet' => 'system/modules/bootstrap-core/assets/css/glyphicons.css',
	'template'   => '<span class="glyphicon glyphicon-%s"></span>',
);

// add layoutType to palette
$GLOBALS['BOOTSTRAP']['layout']['metapalette']['+title'][] = 'layoutType';