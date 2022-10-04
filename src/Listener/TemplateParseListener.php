<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Template;
use ContaoBootstrap\Core\View\Template\Filter\PostRenderFilter;
use ContaoBootstrap\Core\View\Template\Filter\PreRenderFilter;

final class TemplateParseListener
{
    /**
     * @param PreRenderFilter  $preRenderFilter  Pre render filter.
     * @param PostRenderFilter $postRenderFilter Post render filter.
     */
    public function __construct(
        private readonly PreRenderFilter $preRenderFilter,
        private readonly PostRenderFilter $postRenderFilter,
    ) {
    }

    /**
     * Execute all registered template modifiers.
     *
     * @param Template $template Current template.
     *
     * @Hook("parseTemplate")
     */
    public function prepare(Template $template): void
    {
        if (! $this->preRenderFilter->supports($template)) {
            return;
        }

        $this->preRenderFilter->filter($template);
    }

    /**
     * Parse current template.
     *
     * @param string $buffer       Parsed template.
     * @param string $templateName Name of the template.
     *
     * @Hook("parseFrontendTemplate")
     */
    public function parse(string $buffer, string $templateName): string
    {
        if ($this->postRenderFilter->supports($templateName)) {
            $buffer = $this->postRenderFilter->filter($buffer, $templateName);
        }

        return $buffer;
    }
}
