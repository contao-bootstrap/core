<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Subscriber;

use ContaoBootstrap\Core\Config\TypeManager;
use ContaoBootstrap\Core\Config\Model\BootstrapConfigModel;
use ContaoBootstrap\Core\Event\InitializeEnvironmentEvent;
use ContaoBootstrap\Core\Event\InitializeLayoutEvent;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConfigSubscriber handles config loading from the database.
 *
 * @package ContaoBootstrap\Core\Subscriber
 */
class ConfigSubscriber implements EventSubscriberInterface
{
    /**
     * Bootstrap config type manager.
     *
     * @var TypeManager
     */
    private $typeManager;

    /**
     * Database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * ConfigSubscriber constructor.
     *
     * @param TypeManager $typeManager Bootstrap config type manager.
     * @param Connection  $connection  Database connection.
     */
    public function __construct(TypeManager $typeManager, Connection $connection)
    {
        $this->typeManager = $typeManager;
        $this->connection  = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            InitializeEnvironmentEvent::NAME => 'loadGlobalConfig',
            InitializeLayoutEvent::NAME      => 'loadThemeConfig',
        );
    }

    /**
     * Load global configurations from database.
     *
     * @return void
     */
    public function loadGlobalConfig()
    {
        if (!$this->connection->getSchemaManager()->tablesExist('tl_bootstrap_config')) {
            return;
        }

        $collection = BootstrapConfigModel::findGlobalPublished();
        $this->typeManager->buildConfig($collection);
    }

    /**
     * Load theme configuration from database.
     *
     * @param InitializeLayoutEvent $event InitializeLayout event.
     *
     * @return void
     *
     * @internal param Config $config
     */
    public function loadThemeConfig(InitializeLayoutEvent $event)
    {
        $themeId    = $event->getLayoutModel()->pid;
        $collection = BootstrapConfigModel::findPublishedByTheme($themeId);

        $this->typeManager->buildConfig($collection);
    }
}
