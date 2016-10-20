<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\Contao\DataContainer;

use ContaoBootstrap\Core\Environment;

/**
 * Data container helper class for tl_content.
 *
 * @package ContaoBootstrap\Core\Contao\DataContainer
 */
class Content
{
    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * Content constructor.
     */
    public function __construct()
    {
        // TODO: Use Dependency injection.
        $this->environment = \Controller::getContainer()->get('contao_bootstrap.environment');
    }

    /**
     * Get all icons.
     *
     * @return array
     */
    public function getIcons()
    {
        return $this->environment->getIconSet()->getIcons();
    }

    /**
     * Set the icon template.
     *
     * @return void
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function setIconTemplate()
    {
        $GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_icon']['eval']['iconTemplate'] =
            $this->environment->getIconSet()->getTemplate();
    }

    /**
     * Get all data attributes.
     *
     * @return array
     */
    public function getDataAttributes()
    {
        return $this->environment->getConfig()->get('form.data-attributes', array());
    }
}
