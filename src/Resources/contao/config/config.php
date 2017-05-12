<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

// Models
$GLOBALS['TL_MODELS']['tl_bootstrap_config'] = 'ContaoBootstrap\Core\Config\Model\BootstrapConfigModel';

// Modules
$GLOBALS['BE_MOD']['design']['themes']['tables'][] = 'tl_bootstrap_config';

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = ['ContaoBootstrap\Core\Subscriber\HookSubscriber', 'initializeSystem'];
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = ['ContaoBootstrap\Core\Subscriber\HookSubscriber', 'initializeLayout'];
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = ['ContaoBootstrap\Core\Subscriber\TemplateParseSubscriber', 'prepare'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = ['ContaoBootstrap\Core\Subscriber\TemplateParseSubscriber', 'parse'];

if(TL_MODE == 'BE') {
    // Add backend stylesheet
    // TODO: Check if styles are still required
    $GLOBALS['TL_CSS']['bootstrap-core'] = 'bundles/contaobootstrapcore/css/backend.css|all|static';

    // load stylepicker config
    if(\Input::get('key') == 'stylepicker4ward_import') {
        require TL_ROOT . '/vendor/contao-bootstrap/core/src/Resources/contao/onfig/stylepicker.php';
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
