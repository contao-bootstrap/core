<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\View\Template;

/**
 * Interface Modifier describes an template modifier which is called when a template is parsed.
 *
 * @package ContaoBootstrap\Core\View\Template
 */
interface Modifier
{
    /**
     * Check if modifier supports a specific template by its name.
     *
     * @param string $templateName Template name.
     *
     * @return bool
     */
    public function supports($templateName);

    /**
     * Prepare a template before parsing.
     *
     * @param \Template $template Template.
     *
     * @return void
     */
    public function prepare(\Template $template);

    /**
     * Modify the generated output.
     *
     * @param string $buffer       Template output.
     * @param string $templateName Template name.
     *
     * @return string
     */
    public function parse($buffer, $templateName);
}
