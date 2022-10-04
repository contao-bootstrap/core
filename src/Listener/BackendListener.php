<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use Netzmacht\Contao\Toolkit\View\Assets\AssetsManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class BackendListener
{
    public function __construct(
        private readonly AssetsManager $assets,
        private readonly RequestScopeMatcher $scopeMatcher,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (! $this->scopeMatcher->isBackendRequest($request)) {
            return;
        }

        $this->assets->addStylesheet('contao_bootstrap_core::css/backend.css');
    }
}
