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
 * Theme context.
 *
 * @package ContaoBootstrap\Core\Config
 */
interface Context
{
    /**
     * Check if context supports a given context.
     *
     * @param Context $context Another context.
     *
     * @return bool
     */
    public function supports(Context $context): bool;

    /**
     * Check if the context match another context.
     *
     * @param Context $context Context.
     *
     * @return bool
     */
    public function match(Context $context): bool;

    /**
     * Get string representation of context.
     *
     * @return string
     */
    public function __toString(): string;
}
