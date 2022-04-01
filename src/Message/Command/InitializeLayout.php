<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use Contao\LayoutModel;
use Contao\PageModel;
use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

final class InitializeLayout extends Event
{
    public const NAME = 'contao_bootstrap.core.initialize_layout';

    /**
     * The environment.
     */
    private Environment $environment;

    /**
     * The layout model.
     */
    private LayoutModel $layoutModel;

    /**
     * The page model.
     */
    private PageModel $pageModel;

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
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Get layout model.
     */
    public function getLayoutModel(): LayoutModel
    {
        return $this->layoutModel;
    }

    /**
     * Get page model.
     */
    public function getPageModel(): PageModel
    {
        return $this->pageModel;
    }
}
