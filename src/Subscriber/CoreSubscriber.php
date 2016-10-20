<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Subscriber;

use ContaoBootstrap\Core\Bootstrap;
use ContaoBootstrap\Core\Config\Config;
use ContaoBootstrap\Core\Event\InitializeEnvironmentEvent;
use ContaoBootstrap\Core\Event\ReplaceInsertTagsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DefaultSubscriber contains initializes the core.
 *
 * @package ContaoBootstrap\Subscriber
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
        /** @var Config $config */
        $container = \Controller::getContainer();

        // Todo: Is there a more perfomant way to collect the data?
        foreach ($container->getParameter('kernel.bundles') as $name => $bundleClass) {
            $refClass   = new \ReflectionClass($bundleClass);
            $bundleDir  = dirname($refClass->getFileName());
            $configFile = $bundleDir . '/Resources/config/contao-bootstrap.yml';

            if (file_exists($configFile)) {
                $config->merge(
                    Yaml::parse(file_get_contents($configFile))
                );
            }
        }
    }
}
