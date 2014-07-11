<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\ThemePlusImporter;


use Netzmacht\ThemePlusImporter\Event\CollectAssetsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AssetsCollector implements EventSubscriberInterface
{

	/**
	 * @return array The event names to listen to
	 */
	public static function getSubscribedEvents()
	{
		return array(
			CollectAssetsEvent::NAME => array(
				array('detectComponentsAssets'),
				array('detectTwbsBootstrapAssets'),
			),
		);
	}

	/**
	 * @param CollectAssetsEvent $event
	 */
	public function detectComponentsAssets(CollectAssetsEvent $event)
	{
		// scan components-bootstrap
		if(is_dir(TL_ROOT . '/assets/components/bootstrap')) {
			$pattern = TL_ROOT . '/assets/components/bootstrap/{css/*.css,less/*.less}';

			foreach(glob($pattern, GLOB_BRACE) as $file) {
				$event->addStylesheet('components-bootstrap', $file);
			}

			$pattern = TL_ROOT . '/assets/components/bootstrap/js/*.js';

			foreach(glob($pattern) as $file) {
				$event->addJavaScript('components-bootstrap', $file);
			}
		}

		// scan components-bootstrap-default
		if(is_dir(TL_ROOT . '/assets/componentns/bootstrap-default')) {
			$pattern = TL_ROOT . '/assets/components/bootstrap/css/*.css';

			foreach(glob($pattern) as $file) {
				$event->addStylesheet('components-bootstrap', $file);
			}
		}
	}


	/**
	 * @param CollectAssetsEvent $event
	 */
	public function detectTwbsBootstrapAssets(CollectAssetsEvent $event)
	{
		if(is_dir(TL_ROOT . '/composer/vendor/twbs/bootstrap')) {
			$pattern = TL_ROOT . '/composer/vendor/twbs/bootstrap/{css/*.css,less/*.less}';

			foreach(glob($pattern, GLOB_BRACE) as $file) {
				$event->addStylesheet('twbs-bootstrap', $file);
			}

			$pattern = TL_ROOT . '/composer/vendor/twbs/bootstrap/js/*.js';

			foreach(glob($pattern) as $file) {
				$event->addJavaScript('twbs-bootstrap', $file);
			}
		}
	}

} 