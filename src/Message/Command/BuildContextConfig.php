<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Environment\Context;
use Symfony\Contracts\EventDispatcher\Event;

final class BuildContextConfig extends Event
{
    /**
     * @param Environment $environment Environment.
     * @param Context     $context     Context.
     * @param Config      $config      Config.
     */
    public function __construct(
        public readonly Environment $environment,
        public readonly Context $context,
        public Config $config,
    ) {
    }
}
