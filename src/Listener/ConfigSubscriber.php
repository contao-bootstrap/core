<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Environment\ThemeContext;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConfigSubscriber handles config loading from the database.
 *
 * @package ContaoBootstrap\Core\Subscriber
 */
final class ConfigSubscriber implements EventSubscriberInterface
{
    /**
     * Database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Bootstrap application config.
     *
     * @var Config
     */
    private $config;

    /**
     * ConfigSubscriber constructor.
     *
     * @param Connection $connection Database connection.
     * @param Config     $config     Bootstrap application config.
     */
    public function __construct(Connection $connection, Config $config)
    {
        $this->connection = $connection;
        $this->config     = $config;
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
     *
     * @return void
     */
    public function enterApplicationContext(InitializeEnvironment $event): void
    {
        $event->getEnvironment()->enterContext(ApplicationContext::create());
    }

    /**
     * Enter the heme context.
     *
     * @param InitializeLayout $event The subscribed event.
     *
     * @return void
     */
    public function enterThemeContext(InitializeLayout $event): void
    {
        $event->getEnvironment()->enterContext(ThemeContext::forTheme((int) $event->getLayoutModel()->pid));
    }

    /**
     * Build context config.
     *
     * @param BuildContextConfig $command Command.
     *
     * @return void
     */
    public function buildContextConfig(BuildContextConfig $command): void
    {
        $context = $command->getContext();

        if ($context instanceof ApplicationContext) {
            $command->setConfig($this->config);
        }
    }
}
