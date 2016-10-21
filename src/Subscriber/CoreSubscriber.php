<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Subscriber;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment;
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
     * Bootstrap environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * Settings constructor.
     */
    public function __construct()
    {
        // TODO: Use Dependency injection
        $this->environment = \Controller::getContainer()->get('contao_bootstrap.environment');
    }

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
                array('importContaoSettings', 1000)
            ),
            ReplaceInsertTagsEvent::NAME  => 'replaceIconInsertTag',
        );
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
            $icon = $this->environment->getIconSet()->generateIcon($event->getParam(0), $event->getParam(1));

            $event->setHtml($icon);
            $event->stopPropagation();
        }
    }
}
