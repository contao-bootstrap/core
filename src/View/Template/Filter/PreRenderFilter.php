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

namespace ContaoBootstrap\Core\View\Template\Filter;

use Contao\Template;

/**
 * PreFilter is called on the parseTemplate hook.
 *
 * @package ContaoBootstrap\Core\View\Template
 */
interface PreRenderFilter
{
    /**
     * Check if modifier supports a specific template by its name.
     *
     * @param Template $template Template.
     *
     * @return bool
     */
    public function supports(Template $template): bool;

    /**
     * Execute the filter.
     *
     * @param Template $template Template.
     *
     * @return void
     */
    public function filter(Template $template): void;
}
