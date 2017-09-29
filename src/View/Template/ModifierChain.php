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

namespace ContaoBootstrap\Core\View\Template;

use Contao\Template;

/**
 * Class ModifierChain.
 *
 * @package ContaoBootstrap\Core\View\Template
 *
 * @deprecated Get's removed in 2.0.0.
 */
final class ModifierChain implements Modifier
{
    /**
     * Template modifiers.
     *
     * @var Modifier[];
     */
    private $modifiers = [];

    /**
     * ModifierChain constructor.
     *
     * @param array|Modifier[] $modifiers Template modifiers.
     */
    public function __construct(array $modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $templateName): bool
    {
        return $this->modifiers ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(Template $template): void
    {
        foreach ($this->modifiers as $modifier) {
            if ($modifier->supports($template->getName())) {
                $modifier->prepare($template);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $buffer, string $templateName): string
    {
        foreach ($this->modifiers as $modifier) {
            if ($modifier->supports($templateName)) {
                $buffer = $modifier->parse($buffer, $templateName);
            }
        }

        return $buffer;
    }
}
