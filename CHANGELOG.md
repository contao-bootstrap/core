
Changelog
=========

Unreleased
----------

### Added

 - Rewrite configuration system: bundle configuration are used now
 - Provide backend assets system

### Breaking

 - Use event class name as event name
 - Use public properties in message and context objects
 - Drop support for multi column wizard
 - Do not allow string based config paths


2.1.0 (2022-04-20)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.5...2.1.0)

### Added

 - Add support for `contao_bootstrap.yaml` configuration files

### Changed

 - Bump minimum PHP version to 7.4
 - Bump Symfony requirements to ^4.4 or ^5.4
 - Bump Contao requirements to ^4.9 or ^4.13
 - Changed coding standard

### Removed

 - Drop support for stylepicker4ward
 - Removed field `tl_content.bootstrap_dataAttributes`


2.0.5 (2021-02-25)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.4...2.0.5)

 - Define dependencies of the symfony components

2.0.4 (2018-08-27)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.3...2.0.4)

 - Remove doctrine/doctrine-bundle dependency but rely on doctrine/dbal instead

2.0.3 (2018-08-27)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.2...2.0.3)

 - Add Contao 4.6 to the build matrix
 - Remove leftover template modifier, marked as being removed in 2.0.0
 - Update readme (2.0.2)
 - Drop leftover fields and
 - Cleanu

2.0.2 (2018-08-24)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.1...2.0.2)

 - Make environment public temporary.

2.0.1 (2018-07-24)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.0...2.0.1)

 - Update translations
 - Fix incompatibility with PHP 7.0
 - Fix theme context was never entered
 - Add Contao 4.5 to the build matrix
 - Update project files


2.0.0 (2018-01-05)
------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.0-beta2...2.0.0)

 - Support Metapalettes 2.0


2.0.0-beta2 (2017-12-01)
------------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.0-beta1...2.0.0-beta2)

Bugfixes:

 - Mark listeners services as public. Contao requires it.

2.0.0-beta1 (2017-09-29)
------------------------

[Full Changelog](https://github.com/contao-bootstrap/core/compare/2.0.0-alpha5...2.0.0-beta1)

Implemented enhancements:

 - Added template pre and post render filters
 - Deprecate Modifiers. Get removed in 2.0.0.
 - Update readme and add changelog.
