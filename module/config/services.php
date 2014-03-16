<?php

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Environment;
use Netzmacht\Bootstrap\Core\Helper\Icons;

/** @var \Pimple $container */
$container = $GLOBALS['container'];

$container['bootstrap.environment'] = $container->share(function(\Pimple $c) {
	$config = new Config($GLOBALS['BOOTSTRAP']);
	$icons  = new Icons();

	return new Environment($config, $icons, $c['event-dispatcher']);
});