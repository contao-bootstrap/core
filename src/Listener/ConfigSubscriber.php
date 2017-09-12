<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
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
            BuildContextConfig::NAME    => 'buildContextConfig'
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
