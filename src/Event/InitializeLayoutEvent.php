<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InitializeLayoutEvent is emitted when page layout is initialized.
 *
 * @package Netzmacht\Bootstrap\Core\Event
 */
final class InitializeLayoutEvent extends Event
{
    const NAME = 'bootstrap.initialize-layout';

    /**
     * The environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * The layout model.
     *
     * @var \LayoutModel
     */
    private $layoutModel;

    /**
     * The page model.
     *
     * @var \PageModel
     */
    private $pageModel;

    /**
     * Construct.
     *
     * @param Environment  $environment Bootstrap environment.
     * @param \LayoutModel $layoutModel Layout model.
     * @param \PageModel   $pageModel   Page model.
     */
    public function __construct(Environment $environment, \LayoutModel $layoutModel, \PageModel $pageModel)
    {
        $this->environment = $environment;
        $this->layoutModel = $layoutModel;
        $this->pageModel   = $pageModel;
    }

    /**
     * Get bootstrap environment.
     *
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get layout model.
     *
     * @return \LayoutModel
     */
    public function getLayoutModel()
    {
        return $this->layoutModel;
    }

    /**
     * Get page model.
     *
     * @return \PageModel
     */
    public function getPageModel()
    {
        return $this->pageModel;
    }
}
