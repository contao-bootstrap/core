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
 * Class AbstractContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
abstract class AbstractContext implements Context
{
    /**
     * {@inheritDoc}
     */
    public function match(Context $context)
    {
        return $this->__toString() === $context->__toString();
    }
}
