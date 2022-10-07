<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

final class InitializeEnvironment extends Event
{
    public function __construct(public readonly Environment $environment)
    {
    }
}
