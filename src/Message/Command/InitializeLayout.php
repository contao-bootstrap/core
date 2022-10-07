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
        public readonly Environment $environment,
        public readonly LayoutModel $layoutModel,
        public readonly PageModel $pageModel,
    ) {
    }
}
