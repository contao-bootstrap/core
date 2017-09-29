<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

// Models
$GLOBALS['TL_MODELS']['tl_bootstrap_config'] = 'ContaoBootstrap\Core\Config\Model\BootstrapConfigModel';

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = ['contao_bootstrap.core.listener.hook_listener', 'initializeSystem'];
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = ['contao_bootstrap.core.listener.hook_listener', 'initializeLayout'];
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = ['contao_bootstrap.core.listener.parse_template', 'prepare'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = ['contao_bootstrap.core.listener.parse_template', 'parse'];

if(TL_MODE == 'BE') {
    // Add backend stylesheet
    // TODO: Check if styles are still required
    $GLOBALS['TL_CSS']['bootstrap-core'] = 'bundles/contaobootstrapcore/css/backend.css|all|static';

    // load stylepicker config
    if(\Input::get('key') == 'stylepicker4ward_import') {
        require TL_ROOT . '/vendor/contao-bootstrap/core/src/Resources/contao/onfig/stylepicker.php';
    }
}
