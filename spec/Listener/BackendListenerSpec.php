<?php

declare(strict_types=1);

namespace spec\ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Listener\BackendListener;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use Netzmacht\Contao\Toolkit\View\Assets\AssetsManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/** @SuppressWarnings(PHPMD.CamelCaseMethodName) */
final class BackendListenerSpec extends ObjectBehavior
{
    public function let(AssetsManager $assetsManager, RequestScopeMatcher $scopeMatcher): void
    {
        $this->beConstructedWith($assetsManager, $scopeMatcher, ['style.css']);
    }

    public function it_is_initializable(): void
    {
        $this->shouldBeAnInstanceOf(BackendListener::class);
    }

    public function it_registers_styles_in_backend_scope(
        AssetsManager $assetsManager,
        RequestScopeMatcher $scopeMatcher,
        RequestEvent $event,
        Request $request,
    ): void {
        $event->getRequest()
            ->shouldBeCalled()
            ->willReturn($request);

        $scopeMatcher->isBackendRequest($request)
            ->shouldBeCalled()
            ->willReturn(true);

        $assetsManager->addStylesheets(['style.css'])
            ->shouldBeCalled()
            ->willReturn($assetsManager);

        $this->__invoke($event);
    }

    public function it_does_not_register_assets_outside_backend_scope(
        AssetsManager $assetsManager,
        RequestScopeMatcher $scopeMatcher,
        RequestEvent $event,
        Request $request,
    ): void {
        $event->getRequest()
            ->shouldBeCalled()
            ->willReturn($request);

        $scopeMatcher->isBackendRequest($request)
            ->shouldBeCalled()
            ->willReturn(false);

        $assetsManager->addStylesheets(Argument::any())->shouldNotBeCalled();

        $this->__invoke($event);
    }
}
