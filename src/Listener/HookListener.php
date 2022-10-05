<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\LayoutModel;
use Contao\PageModel;
use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class HookListener
{
    /**
     * Construct.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param Environment              $environment     The bootstrap environment.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly Environment $environment,
    ) {
    }

    /**
     * Initialize bootstrap at initialize system hook.
     *
     * @Hook("initializeSystem")
     */
    public function initializeSystem(): void
    {
        $this->initializeEnvironment();
    }

    /**
     * Initialize bootstrap environment.
     */
    protected function initializeEnvironment(): void
    {
        $event = new InitializeEnvironment($this->environment);
        $this->eventDispatcher->dispatch($event);
    }

    /**
     * Initialize Layout.
     *
     * @param PageModel   $page   Current page.
     * @param LayoutModel $layout Page layout.
     *
     * @Hook("getPageLayout")
     */
    public function initializeLayout(PageModel $page, LayoutModel $layout): void
    {
        $environment = $this->environment;
        $environment->setLayout($layout);
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        $environment->setEnabled($layout->layoutType === 'bootstrap');

        $event = new InitializeLayout($environment, $layout, $page);
        $this->eventDispatcher->dispatch($event);
    }
}
