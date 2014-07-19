<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Subscriber;


use Netzmacht\ThemePlusImporter\Event\CollectAssetsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * Class AssetsCollector collects assets which can be imported using the Theme+ importer
 *
 * @package Netzmacht\Bootstrap\Core\Subscriber
 */
class AssetsCollector implements EventSubscriberInterface
{

	/**
	 * @return array The event names to listen to
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'theme-plus-importer.collect-assets' => array(
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
		$length  = strlen(TL_ROOT) + 1;

		// scan components-bootstrap
		if(is_dir(TL_ROOT . '/assets/components/bootstrap')) {
			$pattern = TL_ROOT . '/assets/components/bootstrap/{css/*.css,less/*.less}';

			foreach(glob($pattern, GLOB_BRACE) as $file) {
				$event->addStylesheet('components-bootstrap', substr($file, $length));
			}

			$pattern = TL_ROOT . '/assets/components/bootstrap/js/*.js';

			foreach(glob($pattern) as $file) {
				$event->addJavaScript('components-bootstrap', substr($file, $length));
			}
		}

		// scan components-bootstrap-default
		if(is_dir(TL_ROOT . '/assets/components/bootstrap-default')) {
			$pattern = TL_ROOT . '/assets/components/bootstrap-default/css/*.css';

			foreach(glob($pattern) as $file) {
				$event->addStylesheet('components-bootstrap-default', substr($file, $length));
			}
		}
	}


	/**
	 * @param CollectAssetsEvent $event
	 */
	public function detectTwbsBootstrapAssets(CollectAssetsEvent $event)
	{
		if(is_dir(TL_ROOT . '/composer/vendor/twbs/bootstrap')) {
			$pattern = TL_ROOT . '/composer/vendor/twbs/bootstrap/{dist/css/*.css,less/*.less}';
			$length  = strlen(TL_ROOT) + 1;

			foreach(glob($pattern, GLOB_BRACE) as $file) {
				$event->addStylesheet('twbs-bootstrap', substr($file, $length));
			}

			$pattern = TL_ROOT . '/composer/vendor/twbs/bootstrap/{dist/js,js}/*.js';

			foreach(glob($pattern, GLOB_BRACE) as $file) {
				$event->addJavaScript('twbs-bootstrap', substr($file, $length));
			}
		}
	}

} 