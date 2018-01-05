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
 * Class TemplatePreRenderFilter.
 *
 * @package ContaoBootstrap\Core\View\Template\Filter
 */
class TemplatePreRenderFilter implements PreRenderFilter
{
    /**
     * Post render filters.
     *
     * @var PreRenderFilter[]
     */
    private $filters;

    /**
     * TemplatePreRenderFilter constructor.
     *
     * @param PreRenderFilter[]|array $filters Post render filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Template $template): bool
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($template)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Template $template): void
    {
        foreach ($this->filters as $filter) {
            if ($filter->supports($template)) {
                $filter->filter($template);
            }
        }
    }
}
