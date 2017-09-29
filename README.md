Contao-Bootstrap Core
=====================

[![Build Status](http://img.shields.io/travis/contao-bootstrap/core/master.svg?style=flat-square)](https://travis-ci.org/contao-bootstrap/core)
[![Version](http://img.shields.io/packagist/v/contao-bootstrap/core.svg?style=flat-square)](http://packagist.org/packages/contao-bootstrap/core)
[![License](http://img.shields.io/packagist/l/contao-bootstrap/core.svg?style=flat-square)](http://packagist.org/packages/contao-bootstrap/core)
[![Downloads](http://img.shields.io/packagist/dt/contao-bootstrap/core.svg?style=flat-square)](http://packagist.org/packages/contao-bootstrap/core)
[![Contao Community Alliance coding standard](http://img.shields.io/badge/cca-coding_standard-red.svg?style=flat-square)](https://github.com/contao-community-alliance/coding-standard)

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
$ php contao-manager.phar.php composer require contao-bootstrap/core~2.0@beta

# Using composer directly
$ php composer.phar require contao-bootstrap/core~2.0@beta
```

### Standard edition

Without the contao manager you also have to register the bundle

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
