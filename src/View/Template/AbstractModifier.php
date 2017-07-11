<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\View\Template;

/**
 * Abstract modifier provides a predefined supports($templateName) method.
 *
 * @package ContaoBootstrap\Core\View\Template
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
    public function supports($templateName)
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
