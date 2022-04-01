<?php

declare(strict_types=1);

// Models
$GLOBALS['TL_MODELS']['tl_bootstrap_config'] = 'ContaoBootstrap\Core\Config\Model\BootstrapConfigModel';

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem'][]      = ['contao_bootstrap.core.listener.hook_listener', 'initializeSystem'];
$GLOBALS['TL_HOOKS']['getPageLayout'][]         = ['contao_bootstrap.core.listener.hook_listener', 'initializeLayout'];
$GLOBALS['TL_HOOKS']['parseTemplate'][]         = ['contao_bootstrap.core.listener.parse_template', 'prepare'];
$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = ['contao_bootstrap.core.listener.parse_template', 'parse'];

if (defined('TL_MODE') && TL_MODE === 'BE') {
    // Add backend stylesheet
    $GLOBALS['TL_CSS']['bootstrap-core'] = 'bundles/contaobootstrapcore/css/backend.css|all|static';
}
