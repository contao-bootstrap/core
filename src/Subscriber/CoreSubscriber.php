<?php

namespace Netzmacht\Bootstrap\Core\Subscriber;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DefaultSubscriber
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
     * @param InitializeEnvironmentEvent $event
     */
    public function loadConfig(InitializeEnvironmentEvent $event)
    {
        $config = $event->getEnvironment()->getConfig();

        $this->loadConfigFromModules($config);
        $this->loadConfigFromGlobals($config);
    }

    /**
     * @param InitializeEnvironmentEvent $event
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
     * @param \Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent $event
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
     * @param $config
     */
    private function loadConfigFromModules(Config $config)
    {
        // load config from module files
        $files = glob(TL_ROOT . '/system/modules/*/config/contao-bootstrap.php');

        foreach ($files as $file) {
            $config->import($file);
        }
    }

    /**
     * @param $config
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
