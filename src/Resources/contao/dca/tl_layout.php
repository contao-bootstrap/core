<?php

declare(strict_types=1);

/*
 * Config
 */

$GLOBALS['TL_DCA']['tl_layout']['config']['palettes_callback'][] = [
    'contao_bootstrap.core.listener.layout_dca',
    'generatePalette',
];

/*
 * Fields
 */

$GLOBALS['TL_DCA']['tl_layout']['fields']['name']['eval']['tl_class'] .= ' w50';

$GLOBALS['TL_DCA']['tl_layout']['fields']['layoutType'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_layout']['layoutType'],
    'default'   => 'default',
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['default', 'bootstrap'],
    'reference' => &$GLOBALS['TL_LANG']['tl_layout']['layoutTypes'],
    'eval'      => ['tl_class' => 'w50', 'submitOnChange' => true, 'helpwizard' => true],
    'sql'       => "varchar(150) NOT NULL default ''",
];
