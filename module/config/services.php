<?php

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Environment;
use Netzmacht\Bootstrap\Core\IconSet;

/** @var \Pimple $container */
$container = $GLOBALS['container'];

$container['bootstrap.environment'] = $container->share(function(\Pimple $c) {
	return new Environment(new Config(), new IconSet(), $c['event-dispatcher']);
});

$container['bootstrap.config-type-factory'] = $container->share(function(\Pimple $c) {
    $config = $c['bootstrap.environment']->getConfig();

    return new Config\ConfigTypeFactory($config->get('config.types'));
});