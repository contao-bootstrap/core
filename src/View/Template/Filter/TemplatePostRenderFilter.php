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
 * Class TemplatePostRenderFilter.
 *
 * @package ContaoBootstrap\Core\View\Template\Filter
 */
class TemplatePostRenderFilter implements PostRenderFilter
{
    /**
     * Post render filters.
     *
     * @var PostRenderFilter[]
     */
    private $filters;

    /**
     * TemplatePostRenderFilter constructor.
     *
     * @param PostRenderFilter[]|array $filters Post render filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $templateName): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($templateName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(string $buffer, string $templateName): string
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($templateName)) {
                $buffer = $filter->filter($buffer, $templateName);
            }
        }

        return $buffer;
    }
}
