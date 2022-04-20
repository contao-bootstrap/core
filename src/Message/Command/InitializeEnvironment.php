<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

final class InitializeEnvironment extends Event
{
    public const NAME = 'contao_bootstrap.core.initialize_environment';

    /**
     * Bootstrap environment.
     */
    protected Environment $environment;

    /**
     * Construct.
     *
     * @param Environment $environment Bootstrap environment.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get the Bootstrap environment.
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }
}
