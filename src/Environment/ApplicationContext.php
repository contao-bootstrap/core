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
 * Class ApplicationContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
final class ApplicationContext extends AbstractContext
{
    /**
     * {@inheritDoc}
     */
    public function supports(Context $context): bool
    {
        return $context instanceof ApplicationContext;
    }

    /**
     * {@inheritDoc}
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return 'application';
    }
}
