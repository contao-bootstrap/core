<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\View\Template;

/**
 * AbstractPrepareModifier provides a base parse method. So you only have to add prepare method.
 *
 * @package ContaoBootstrap\Core\View\Template
 */
abstract class AbstractPrepareModifier extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function parse($buffer, $templateName)
    {
        return $buffer;
    }
}
