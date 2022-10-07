<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\ThemeContext;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;

final class ConfigSubscriber
{
    /** @param Config $config Bootstrap application config. */
    public function __construct(private readonly Config $config)
    {
    }

    /**
     * Initialize environment.
     *
     * @param InitializeEnvironment $event The event.
     */
    public function enterApplicationContext(InitializeEnvironment $event): void
    {
        $event->environment->enterContext(ApplicationContext::create());
    }

    /**
     * Enter the heme context.
     *
     * @param InitializeLayout $event The subscribed event.
     */
    public function enterThemeContext(InitializeLayout $event): void
    {
        $event->environment->enterContext(ThemeContext::forTheme((int) $event->layoutModel->pid));
    }

    /**
     * Build context config.
     *
     * @param BuildContextConfig $command Command.
     */
    public function buildContextConfig(BuildContextConfig $command): void
    {
        if (! ($command->context instanceof ApplicationContext)) {
            return;
        }

        $command->config = $this->config;
    }
}
