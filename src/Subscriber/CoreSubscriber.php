<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Subscriber;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DefaultSubscriber contains initializes the core.
 *
 * @package Netzmacht\Bootstrap\Subscriber
 */
class CoreSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            InitializeEnvironmentEvent::NAME => array(
                array('loadConfig', 1000),
                array('importContaoSettings', 1000)
            ),
            ReplaceInsertTagsEvent::NAME  => 'replaceIconInsertTag',
        );
    }

    /**
     * Load configuration.
     *
     * @param InitializeEnvironmentEvent $event Initialize environment event.
     *
     * @return void
     */
    public function loadConfig(InitializeEnvironmentEvent $event)
    {
        $config = $event->getEnvironment()->getConfig();

        $this->loadConfigFromModules($config);
        $this->loadConfigFromGlobals($config);
    }

    /**
     * Import Contao settings into config.
     *
     * @param InitializeEnvironmentEvent $event Initialisation event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function importContaoSettings(InitializeEnvironmentEvent $event)
    {
        $config = $event->getEnvironment()->getConfig();

        if ($GLOBALS['TL_CONFIG']['bootstrapIconSet']) {
            $config->set('icons.active', $GLOBALS['TL_CONFIG']['bootstrapIconSet']);
        }
    }

    /**
     * Replace insert tags.
     *
     * @param ReplaceInsertTagsEvent $event Replace insert tags event.
     *
     * @return void
     */
    public function replaceIconInsertTag(ReplaceInsertTagsEvent $event)
    {
        if ($event->getTag() == 'icon' || $event->getTag() == 'i') {
            $icon = Bootstrap::generateIcon($event->getParam(0), $event->getParam(1));

            $event->setHtml($icon);
            $event->stopPropagation();
        }
    }

    /**
     * Load config of each module.
     *
     * @param Config $config Bootstrap configuration.
     *
     * @return void
     */
    private function loadConfigFromModules(Config $config)
    {
        foreach (\Config::getInstance()->getActiveModules() as $module) {
            $file = TL_ROOT . '/system/modules/' . $module . '/config/contao-bootstrap.php';

            if (file_exists($file)) {
                $config->import($file);
            }
        }
    }

    /**
     * Load deprecated global config.
     *
     * @param Config $config Bootstrap config.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function loadConfigFromGlobals(Config $config)
    {
        // support deprecated config
        if (isset($GLOBALS['BOOTSTRAP'])) {
            $config->merge($GLOBALS['BOOTSTRAP']);
        }
    }
}
