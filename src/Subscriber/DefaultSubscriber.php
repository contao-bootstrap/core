<?php

namespace Netzmacht\Bootstrap\Core\Subscriber;

use Contao\LayoutModel;
use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Event\Events;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\InitializeLayoutEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent;
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
			InitializeEnvironmentEvent::NAME => array('importContaoSettings', 1000),
			ReplaceInsertTagsEvent::NAME     => 'replaceIconInsertTag',
		);
	}


	/**
	 * @param InitializeEnvironmentEvent $event
	 */
	public function importContaoSettings(InitializeEnvironmentEvent $event)
	{
		$config = $event->getEnvironment()->getConfig();

		if($GLOBALS['TL_CONFIG']['bootstrapIconSet']) {
			$config->set('icons.active', $GLOBALS['TL_CONFIG']['bootstrapIconSet']);
		}
	}


	/**
	 * @param \Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent $event
	 */
	public function replaceIconInsertTag(ReplaceInsertTagsEvent $event)
	{
		if($event->getTag() == 'icon' || $event->getTag() == 'i') {
			$icon = Bootstrap::generateIcon($event->getParam(0), $event->getParam(1));

			$event->setHtml($icon);
			$event->stopPropagation();
		}
	}

}