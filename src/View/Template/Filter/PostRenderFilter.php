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

/**
 * Post filter is called on the parseFrontendTemplate hook.
 *
 * @package ContaoBootstrap\Core\View\Template
 */
interface PostRenderFilter
{
    /**
     * Check if modifier supports a specific template by its name.
     *
     * @param string $templateName Template name.
     *
     * @return bool
     */
    public function supports(string $templateName): bool;

    /**
     * Execute the filter.
     *
     * @param string $buffer       Template output.
     * @param string $templateName Template name.
     *
     * @return string
     */
    public function filter(string $buffer, string $templateName): string;
}
