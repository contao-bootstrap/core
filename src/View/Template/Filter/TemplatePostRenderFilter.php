<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template\Filter;

use Override;

final class TemplatePostRenderFilter implements PostRenderFilter
{
    /**
     * Post render filters.
     *
     * @var iterable<PostRenderFilter>
     */
    private iterable $filters;

    /** @param iterable<PostRenderFilter> $filters Post render filters */
    public function __construct(iterable $filters)
    {
        $this->filters = $filters;
    }

    #[Override]
    public function supports(string $templateName): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($templateName)) {
                return true;
            }
        }

        return false;
    }

    #[Override]
    public function filter(string $buffer, string $templateName): string
    {
        foreach ($this->filters as $filter) {
            if (! $filter->supports($templateName)) {
                continue;
            }

            $buffer = $filter->filter($buffer, $templateName);
        }

        return $buffer;
    }
}
