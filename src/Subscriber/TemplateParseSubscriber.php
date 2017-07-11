<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Subscriber;

use ContaoBootstrap\Core\View\Template\Modifier;

/**
 * Class TemplateModifier contains all template modifiers used by bootstrap config.
 *
 * @package ContaoBootstrap
 */
class TemplateParseSubscriber
{
    /**
     * Template modifier.
     *
     * @var Modifier
     */
    private $modifier;

    /**
     * Modifier constructor.
     *
     * @param Modifier $modifier Template modifier.
     */
    public function __construct(Modifier $modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * Execute all registered template modifiers.
     *
     * @param \Template $template Current template.
     *
     * @return void
     */
    public function prepare(\Template $template)
    {
        if ($this->modifier->supports($template->getName())) {
            $this->modifier->prepare($template);
        }
    }

    /**
     * Parse current template.
     *
     * @param string $buffer       Parsed template.
     * @param string $templateName Name of the template.
     *
     * @return string
     */
    public function parse($buffer, $templateName)
    {
        if ($this->modifier->supports($templateName)) {
            $buffer = $this->modifier->parse($buffer, $templateName);
        }

        return $buffer;
    }
}
