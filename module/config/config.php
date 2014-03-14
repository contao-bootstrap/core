<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem']['bootstrap-core']  = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'initializeSystem');
$GLOBALS['TL_HOOKS']['replaceInsertTags']['bootstrap-core'] = array('Netzmacht\Bootstrap\Core\Contao\Hooks', 'replaceInsertTags');

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';

