<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Template;

use Contao\Template;

/**
 * Class ModifierChain.
 *
 * @package ContaoBootstrap\Core\View\Template
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
