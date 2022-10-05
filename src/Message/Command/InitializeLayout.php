<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use Contao\LayoutModel;
use Contao\PageModel;
use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

final class InitializeLayout extends Event
{
    /**
     * Construct.
     *
     * @param Environment $environment Bootstrap environment.
     * @param LayoutModel $layoutModel Layout model.
     * @param PageModel   $pageModel   Page model.
     */
    public function __construct(
        private readonly Environment $environment,
        private readonly LayoutModel $layoutModel,
        private readonly PageModel $pageModel,
    ) {
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
