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
        private readonly Environment $environment,
        private readonly Context $context,
        private Config $config,
    ) {
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

    /**
     * Get config.
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Set config.
     *
     * @param Config $config Config.
     *
     * @return $this
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }
}
