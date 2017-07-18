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
 * Class ApplicationContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
class ApplicationContext extends AbstractContext
{
    /**
     * {@inheritDoc}
     */
    public function supports(Context $context)
    {
        return $context instanceof ApplicationContext;
    }

    /**
     * {@inheritDoc}
     */
    public static function create()
    {
        return new static();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return 'application';
    }
}
