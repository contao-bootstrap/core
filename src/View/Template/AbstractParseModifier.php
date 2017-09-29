<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template;

use Contao\Template;

/**
 * AbstractPrepareModifier provides a base prepare method. So you only have to add parse method.
 *
 * @package ContaoBootstrap\Core\View\Template
 *
 * @deprecated Get's removed in 2.0.0.
 */
abstract class AbstractParseModifier extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function prepare(Template $template): void
    {
    }
}
