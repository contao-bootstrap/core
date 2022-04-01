<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template\Filter;

interface PostRenderFilter
{
    /**
     * Check if modifier supports a specific template by its name.
     *
     * @param string $templateName Template name.
     */
    public function supports(string $templateName): bool;

    /**
     * Execute the filter.
     *
     * @param string $buffer       Template output.
     * @param string $templateName Template name.
     */
    public function filter(string $buffer, string $templateName): string;
}
