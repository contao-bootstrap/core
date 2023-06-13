Contao-Bootstrap Core
=====================

[![Version](https://img.shields.io/packagist/v/contao-bootstrap/core.svg?style=for-the-badge&label=Latest)](http://packagist.org/packages/contao-bootstrap/core)
[![GitHub issues](https://img.shields.io/github/issues/contao-bootstrap/core.svg?style=for-the-badge&logo=github)](https://github.com/contao-bootstrap/core/issues)
[![License](https://img.shields.io/packagist/l/contao-bootstrap/core.svg?style=for-the-badge&label=License)](http://packagist.org/packages/contao-bootstrap/core)
[![Build Status](https://img.shields.io/github/workflow/status/contao-bootstrap/core/contao-bootrap-core/master?style=for-the-badge)](https://github.com/contao-bootstrap/core/actions/workflows/diagnostics.yml)
[![Downloads](https://img.shields.io/packagist/dt/contao-bootstrap/core.svg?style=for-the-badge&label=Downloads)](http://packagist.org/packages/contao-bootstrap/core)

This extension provides Bootstrap 4 integration into Contao.

Contao-Bootstrap is a modular integration. The core components provides the infrastructure for other components.

Features
--------

 - Bootstrap environment
 - Config system
 - Template pre and post render filters

Changelog
---------

See [changelog](CHANGELOG.md)

Requirements
------------

 - PHP ^8.1
 - Contao ^4.13 || ^5.0


Install
-------

### Managed edition

When using the managed edition it's pretty simple to install the package. Just search for the package in the
Contao Manager and install it. Alternatively you can use the CLI.

```bash
# Using the contao manager
$ php contao-manager.phar.php composer require contao-bootstrap/core^3.0

# Using composer directly
$ php composer.phar require contao-bootstrap/core^3.0
```

### Symfony application

If you use Contao in a symfony application without contao/manager-bundle, you have to register following bundles
manually and register the bundle configuration:

```php

final class AppKernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new \ContaoCommunityAlliance\MetaPalettes\CcaMetaPalettesBundle(),
            new \Netzmacht\Contao\Toolkit\Bundle\NetzmachtContaoToolkitBundle(),
            new \ContaoBootstrap\Core\ContaoBootstrapCoreBundle(),
        ];
    }
}

```

```yaml

# Application config.yaml
imports:
  - { resource: vendor/contao-bootstrap/core/Resources/config/contao_bootstrap.yaml }

```
