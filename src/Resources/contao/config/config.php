<?php

declare(strict_types=1);

if (defined('TL_MODE') && TL_MODE === 'BE') {
    // Add backend stylesheet
    $GLOBALS['TL_CSS']['bootstrap-core'] = 'bundles/contaobootstrapcore/css/backend.css|all|static';
}
