<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

final class InitializeEnvironment extends Event
{
    public const NAME = 'contao_bootstrap.core.initialize_environment';

    public function __construct(private readonly Environment $environment)
    {
    }

    /**
     * Get the Bootstrap environment.
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }
}
