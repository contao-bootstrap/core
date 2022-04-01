<?php

declare(strict_types=1);

/*
 * define default bootstrap palette
 */

$GLOBALS['TL_DCA']['tl_content']['metapalettes']['_bootstrap_default_'] = [
    'type'      => ['type', 'headline'],
    'link'      => [],
    'config'    => [],
    'protected' => [':hide', 'protected'],
    'expert'    => [':hide', 'guests', 'cssID', 'space'],
    'invisible' => [':hide', 'invisible', 'start', 'stop'],
];

/*
 * bootstrap empty palettes
 */

$GLOBALS['TL_DCA']['tl_content']['metapalettes']['_bootstrap_empty_'] = [
    'type' => ['type'],
];

/*
 * Bootstrap parent palette.
 */

$GLOBALS['TL_DCA']['tl_content']['metapalettes']['bootstrap_parent extends _bootstrap_empty_'] = [
    '+type' => ['bootstrap_parentId'],
];
