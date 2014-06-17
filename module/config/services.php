<?php

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Environment;
use Netzmacht\Bootstrap\Core\IconSet;

/** @var \Pimple $container */
$container = $GLOBALS['container'];

$container['bootstrap.environment'] = $container->share(function(\Pimple $c) {
	$config = new Config($GLOBALS['BOOTSTRAP']);

	return new Environment($config, $c['bootstrap.icon-set'], $c['event-dispatcher']);
});

$container['bootstrap.icon-set'] = $container->share(function(\Pimple $c) {
	return new IconSet();
});