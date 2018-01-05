<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

/**
 * Class AbstractContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
abstract class AbstractContext implements Context
{
    /**
     * {@inheritDoc}
     */
    public function match(Context $context): bool
    {
        return $this->__toString() === $context->__toString();
    }
}
