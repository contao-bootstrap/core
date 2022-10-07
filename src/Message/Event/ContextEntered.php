<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Event;

use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Environment\Context;
use Symfony\Contracts\EventDispatcher\Event;

final class ContextEntered extends Event
{
    /**
     * @param Environment $environment Bootstrap environment.
     * @param Context     $context     Environment context.
     */
    public function __construct(public readonly Environment $environment, public readonly Context $context)
    {
    }
}
