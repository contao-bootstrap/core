<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

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

    return new Config\TypeFactory($config->get('config.types', array()));
});

$container['bootstrap.config-type-manager'] = $container->share(function(\Pimple $c) {
    $config  = $c['bootstrap.environment']->getConfig();
    $factory = $c['bootstrap.config-type-factory'];

    return new Config\TypeManager($config, $factory->createAll());
});
