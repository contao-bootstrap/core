<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template\Filter;

use Contao\Template;
use Override;

final class TemplatePreRenderFilter implements PreRenderFilter
{
    /**
     * Post render filters.
     *
     * @var iterable<PreRenderFilter>
     */
    private iterable $filters;

    /** @param iterable<PreRenderFilter> $filters Post render filters */
    public function __construct(iterable $filters)
    {
        $this->filters = $filters;
    }

    #[Override]
    public function supports(Template $template): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($template)) {
                return true;
            }
        }

        return false;
    }

    #[Override]
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
