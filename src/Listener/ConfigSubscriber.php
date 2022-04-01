<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\ThemeContext;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ConfigSubscriber implements EventSubscriberInterface
{
    /**
     * Bootstrap application config.
     */
    private Config $config;

    /**
     * @param Config $config Bootstrap application config.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            InitializeEnvironment::NAME => 'enterApplicationContext',
            InitializeLayout::NAME      => 'enterThemeContext',
            BuildContextConfig::NAME    => 'buildContextConfig',
        ];
    }

    /**
     * Initialize environment.
     *
     * @param InitializeEnvironment $event The event.
     */
    public function enterApplicationContext(InitializeEnvironment $event): void
    {
        $event->getEnvironment()->enterContext(ApplicationContext::create());
    }

    /**
     * Enter the heme context.
     *
     * @param InitializeLayout $event The subscribed event.
     */
    public function enterThemeContext(InitializeLayout $event): void
    {
        $event->getEnvironment()->enterContext(ThemeContext::forTheme((int) $event->getLayoutModel()->pid));
    }

    /**
     * Build context config.
     *
     * @param BuildContextConfig $command Command.
     */
    public function buildContextConfig(BuildContextConfig $command): void
    {
        $context = $command->getContext();

        if (! ($context instanceof ApplicationContext)) {
            return;
        }

        $command->setConfig($this->config);
    }
}
