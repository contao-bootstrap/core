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

namespace ContaoBootstrap\Core\Message\Command;

use Contao\LayoutModel;
use Contao\PageModel;
use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class InitializeLayoutEvent is emitted when page layout is initialized.
 *
 * @package ContaoBootstrap\Core\Event
 */
final class InitializeLayout extends Event
{
    const NAME = 'contao_bootstrap.core.initialize_layout';

    /**
     * The environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * The layout model.
     *
     * @var LayoutModel
     */
    private $layoutModel;

    /**
     * The page model.
     *
     * @var PageModel
     */
    private $pageModel;

    /**
     * Construct.
     *
     * @param Environment $environment Bootstrap environment.
     * @param LayoutModel $layoutModel Layout model.
     * @param PageModel   $pageModel   Page model.
     */
    public function __construct(Environment $environment, LayoutModel $layoutModel, PageModel $pageModel)
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
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Get layout model.
     *
     * @return LayoutModel
     */
    public function getLayoutModel(): LayoutModel
    {
        return $this->layoutModel;
    }

    /**
     * Get page model.
     *
     * @return PageModel
     */
    public function getPageModel(): PageModel
    {
        return $this->pageModel;
    }
}
