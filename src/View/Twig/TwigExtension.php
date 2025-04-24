<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\View\Twig;

use ContaoBootstrap\Core\Environment;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    public function __construct(private readonly Environment $environment)
    {
    }

    /** {@inheritDoc} */
    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('contao_bootstrap_config', fn () => $this->environment->getConfig()),
            new TwigFunction('contao_bootstrap_enabled', fn () => $this->environment->enabled),
        ];
    }
}
