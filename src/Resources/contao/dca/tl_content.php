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

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_dataAttributes'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'tl_class'     => 'clr',
        'columnFields' => [
            'name'  => [
                'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes_name'],
                'exclude'   => true,
                'inputType' => 'select',
                'options_callback' => ['contao_bootstrap.core.listener.content_dca', 'getDataAttributes'],
                'reference' => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_buttons_types'],
                'eval'      => ['style' => 'width: 145px;', 'includeBlankOption' => true, 'chosen' => true],
            ],
            'value' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_content']['bootstrap_dataAttributes_value'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => ['style' => 'width: 160px', 'allowHtml' => true],
            ],
        ],
    ],
    'sql'       => 'blob NULL',
];
