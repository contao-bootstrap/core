<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Subscriber;

use Netzmacht\Bootstrap\Core\Config\TypeManager;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\InitializeLayoutEvent;
use Netzmacht\Bootstrap\Core\Util\Contao;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConfigSubscriber handles config loading from the database
 *
 * @package Netzmacht\Bootstrap\Core\Subscriber
 */
class ConfigSubscriber implements EventSubscriberInterface
{
    /**
     * @{inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            InitializeEnvironmentEvent::NAME => 'loadGlobalConfig',
            InitializeLayoutEvent::NAME      => 'loadThemeConfig',
        );
    }

    /**
     * Load global configurations from database
     */
    public function loadGlobalConfig()
    {
        // prevent that database is loaded before user object
        Contao::intializeObjectStack();

        if (!\Database::getInstance()->tableExists('tl_bootstrap_config')) {
            return;
        }

        $collection = BootstrapConfigModel::findGlobalPublished();
        $this->getTypeManager()->buildConfig($collection);
    }

    /**
     * Load theme configuration from database
     *
     * @param InitializeLayoutEvent $event
     * @internal param Config $config
     */
    public function loadThemeConfig(InitializeLayoutEvent $event)
    {
        $themeId    = $event->getLayoutModel()->pid;
        $collection = BootstrapConfigModel::findPublishedByTheme($themeId);

        $this->getTypeManager()->buildConfig($collection);
    }

    /**
     * @return TypeManager
     */
    private function getTypeManager()
    {
        return $GLOBALS['container']['bootstrap.config-type-manager'];
    }
}
