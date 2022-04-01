<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template\Filter;

use Contao\Template;

interface PreRenderFilter
{
    /**
     * Check if modifier supports a specific template by its name.
     *
     * @param Template $template Template.
     */
    public function supports(Template $template): bool;

    /**
     * Execute the filter.
     *
     * @param Template $template Template.
     */
    public function filter(Template $template): void;
}
