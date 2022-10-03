<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use Netzmacht\Contao\Toolkit\View\Assets\AssetsManager;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class BackendListener
{
    private AssetsManager $assets;

    private RequestScopeMatcher $scopeMatcher;

    public function __construct(AssetsManager $assets, RequestScopeMatcher $scopeMatcher)
    {
        $this->assets       = $assets;
        $this->scopeMatcher = $scopeMatcher;
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
