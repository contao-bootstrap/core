<?php

// Hooks
$GLOBALS['TL_HOOKS']['initializeSystem']['bootstrap-core']  = array('Netzmacht\Bootstrap\Core\Hooks', 'initializeEnvironment');
$GLOBALS['TL_HOOKS']['replaceInsertTags']['bootstrap-core'] = array('Netzmacht\Bootstrap\Core\Hooks', 'replaceInsertTags');

// Event subscribers
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'Netzmacht\Bootstrap\Core\Subscriber\DefaultSubscriber';

