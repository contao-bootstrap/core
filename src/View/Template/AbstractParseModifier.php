<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\View\Template;

/**
 * AbstractPrepareModifier provides a base prepare method. So you only have to add parse method.
 *
 * @package ContaoBootstrap\Core\View\Template
 */
abstract class AbstractParseModifier extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function prepare(\Template $template)
    {
    }
}
