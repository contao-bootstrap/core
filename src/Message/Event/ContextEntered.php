<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Event;

use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Environment\Context;
use Symfony\Contracts\EventDispatcher\Event;

final class ContextEntered extends Event
{
    public const NAME = 'contao_bootstrap.core.enter_context';

    /**
     * @param Environment $environment Bootstrap environment.
     * @param Context     $context     Environment context.
     */
    public function __construct(private readonly Environment $environment, private readonly Context $context)
    {
    }

    /**
     * Get environment.
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Get context.
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}
