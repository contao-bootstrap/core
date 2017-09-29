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

namespace ContaoBootstrap\Core\View\Template;

/**
 * AbstractPrepareModifier provides a base parse method. So you only have to add prepare method.
 *
 * @package ContaoBootstrap\Core\View\Template
 *
 * @deprecated Get's removed in 2.0.0.
 */
abstract class AbstractPrepareModifier extends AbstractModifier
{
    /**
     * {@inheritdoc}
     */
    public function parse(string $buffer, string $templateName): string
    {
        return $buffer;
    }
}
