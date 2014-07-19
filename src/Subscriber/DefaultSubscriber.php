<?php

namespace Netzmacht\Bootstrap\Core\Subscriber;

use Contao\LayoutModel;
use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Event\Events;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagEvent;
use Netzmacht\Bootstrap\Core\Event\RewriteCssClassesEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DefaultSubscriber
 * @package Netzmacht\Bootstrap\Subscriber
 */
class DefaultSubscriber implements EventSubscriberInterface
{

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * The array keys are event names and the value can be:
	 *
	 *  * The method name to call (priority defaults to 0)
	 *  * An array composed of the method name to call and the priority
	 *  * An array of arrays composed of the method names to call and respective
	 *    priorities, or 0 if unset
	 *
	 * For instance:
	 *
	 *  * array('eventName' => 'methodName')
	 *  * array('eventName' => array('methodName', $priority))
	 *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
	 *
	 * @return array The event names to listen to
	 *
	 * @api
	 */
	public static function getSubscribedEvents()
	{
		return array(
			Events::INITIALZE           => array('importContaoSettings', 1000),
			Events::SELECT_ICON_SET     => array('selectIconSet', 1000),
            Events::REWRITE_CSS_CLASSES => array('rewriteCssClasses', 1000),
		);
	}


	/**
	 * @param InitializeEnvironmentEvent $event
	 */
	public function importContaoSettings(InitializeEnvironmentEvent $event)
	{
		$config = $event->getConfig();

		if($GLOBALS['TL_CONFIG']['bootstrapIconSet']) {
			$config->set('icons.active', $GLOBALS['TL_CONFIG']['bootstrapIconSet']);
		}
	}


	/**
	 * @param \Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagEvent $event
	 */
	public function replaceIconInsertTag(ReplaceInsertTagEvent $event)
	{
		if($event->getTag() == 'icon' || $event->getTag() == 'i') {
			$icon = Bootstrap::generateIcon($event->getParam(0), $event->getParam(1));

			$event->setHtml($icon);
			$event->stopPropagation();
		}
	}


    /**
     * @param RewriteCssClassesEvent $event
     */
    public function rewriteCssClasses(RewriteCssClassesEvent $event)
    {
        $config = Bootstrap::getConfigVar('templates.rewrite-css-classes', array());
        $event->addClasses($config);
    }

}