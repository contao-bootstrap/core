<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template\Filter;

use Contao\Template;

class TemplatePreRenderFilter implements PreRenderFilter
{
    /**
     * Post render filters.
     *
     * @var PreRenderFilter[]
     */
    private array $filters;

    /**
     * @param PreRenderFilter[]|array $filters Post render filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function supports(Template $template): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($template)) {
                return true;
            }
        }

        return false;
    }

    public function filter(Template $template): void
    {
        foreach ($this->filters as $filter) {
            if (! $filter->supports($template)) {
                continue;
            }

            $filter->filter($template);
        }
    }
}
