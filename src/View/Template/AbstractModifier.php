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

/**
 * Abstract modifier provides a predefined supports($templateName) method.
 *
 * @package ContaoBootstrap\Core\View\Template
 *
 * @deprecated Get's removed in 2.0.0.
 */
abstract class AbstractModifier implements Modifier
{
    /**
     * List of supported template names.
     *
     * It's allowed to wildcard a template name pattern, e.g. fe_*.
     *
     * @var array
     */
    private $templateNames = [];

    /**
     * AbstractModifier constructor.
     *
     * @param array $templateNames List of supported template names.
     */
    public function __construct(array $templateNames)
    {
        $this->templateNames = $templateNames;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $templateName): bool
    {
        foreach ($this->templateNames as $supported) {
            if ($templateName === $supported) {
                return true;
            }

            if (substr($supported, -1) === '*'
                && 0 == strcasecmp(substr($supported, 0, -1), substr($templateName, 0, (strlen($supported) - 1)))
            ) {
                return true;
            }
        }

        return false;
    }
}
