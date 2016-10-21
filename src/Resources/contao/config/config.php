<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

// Models
$GLOBALS['TL_MODELS']['tl_bootstrap_config'] = 'ContaoBootstrap\Core\Config\Model\BootstrapConfigModel';

// Modules
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_bootstrap_config';

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = ['ContaoBootstrap\Core\Subscriber\HookSubscriber', 'initializeSystem'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = ['ContaoBootstrap\Core\Subscriber\HookSubscriber', 'replaceInsertTags'];
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = ['ContaoBootstrap\Core\Subscriber\HookSubscriber', 'initializeLayout'];
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = ['ContaoBootstrap\Core\View\Template\Modifier', 'modify'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = ['ContaoBootstrap\Core\View\Template\Modifier', 'parse'];

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoBootstrap\Core\Subscriber\CoreSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoBootstrap\Core\Subscriber\ConfigSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoBootstrap\Core\Subscriber\AssetsCollector';

if(TL_MODE == 'BE') {
    // Add backend stylesheet
    // TODO: Check if styles are still required
    $GLOBALS['TL_CSS']['bootstrap-core'] = 'system/modules/bootstrap-core/assets/css/backend.css|all|static';

    // load stylepicker config
    if(\Input::get('key') == 'stylepicker4ward_import') {
        require TL_ROOT . '/system/modules/bootstrap-core/config/stylepicker.php';
    }
}

// Easy themes support for Contao Bootstrap config
$GLOBALS['TL_EASY_THEMES_MODULES']['bootstrap_config'] = array
(
    'title'         => &$GLOBALS['TL_LANG']['bootstrapConfig'][1],
    'label'         => &$GLOBALS['TL_LANG']['bootstrapConfig'][0],
    'href_fragment' => 'table=tl_bootstrap_config',
    'icon'          => 'system/modules/bootstrap-core/assets/img/bootstrap.png',
    'appendRT'      => true
);
