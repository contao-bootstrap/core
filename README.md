Contao-Bootstrap Core
=====================

[![Version](http://img.shields.io/packagist/v/contao-bootstrap/core.svg?style=for-the-badge&label=Latest)](http://packagist.org/packages/contao-bootstrap/core)
[![GitHub issues](https://img.shields.io/github/issues/contao-bootstrap/core.svg?style=for-the-badge&logo=github)](https://github.com/contao-bootstrap/core/issues)
[![License](http://img.shields.io/packagist/l/contao-bootstrap/core.svg?style=for-the-badge&label=License)](http://packagist.org/packages/contao-bootstrap/core)
[![Build Status](http://img.shields.io/travis/contao-bootstrap/core/master.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/contao-bootstrap/core)
[![Downloads](http://img.shields.io/packagist/dt/contao-bootstrap/core.svg?style=for-the-badge&label=Downloads)](http://packagist.org/packages/contao-bootstrap/core)

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

 - PHP 7.1
 - Contao ~4.4
 
 
Install
-------

### Managed edition

When using the managed edition it's pretty simple to install the package. Just search for the package in the
Contao Manager and install it. Alternatively you can use the CLI.  

```bash
# Using the contao manager
$ php contao-manager.phar.php composer require contao-bootstrap/core~2.0

# Using composer directly
$ php composer.phar require contao-bootstrap/core~2.0
```

### Symfony application

If you use Contao in a symfony application without contao/manager-bundle, you have to register following bundles 
manually:

```php

class AppKernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Contao\CoreBundle\HttpKernel\Bundle\ContaoModuleBundle('metapalettes', $this->getRootDir()),
            new ContaoBootstrap\Core\ContaoBootstrapCoreBundle(),
        ];
    }
}

```
