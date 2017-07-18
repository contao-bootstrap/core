<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

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
    public function supports(Context $context);

    /**
     * Check if the context match another context.
     *
     * @param Context $context Context.
     *
     * @return bool
     */
    public function match(Context $context);

    /**
     * Get string representation of context.
     *
     * @return string
     */
    public function __toString();
}
