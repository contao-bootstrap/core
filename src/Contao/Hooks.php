<?php

namespace Netzmacht\Bootstrap\Core\Contao;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Environment;
use Netzmacht\Bootstrap\Core\Event\Events;
use Netzmacht\Bootstrap\Core\Event\InitializeEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagEvent;
use Netzmacht\Bootstrap\Core\Event\LoadDynamicTemplatesEvent;
use Netzmacht\Bootstrap\Core\Event\SelectIconSetEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Hooks
{

	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;


	/**
	 *
	 */
	function __construct()
	{
		$this->eventDispatcher = $GLOBALS['container']['event-dispatcher'];
	}


	/**
	 * @param $tag
	 * @param bool $cache
	 * @return string
	 */
	public function replaceInsertTags($tag, $cache = true)
	{
		$params = explode('::', $tag);
		$tag    = array_shift($params);
		$event  = new ReplaceInsertTagEvent($tag, $params, $cache);

		$this->eventDispatcher->dispatch(Events::REPLACE_INSERT_TAGS, $event);

		return $event->getHtml() ? : false;
	}


	/**
	 * Initialize bootstrap at initialize system hook
	 */
	public function initializeSystem()
	{
		$this->initializeEnvironment();
		$this->selectIconSet();
	}


	/**
	 * Initialize bootstrap environment
	 */
	protected function initializeEnvironment()
	{
		$environment = Environment::getInstance();
		$config      = $environment->getConfig();
		$enabled     = $environment->isEnabled();

		// initialize event
		$event = new InitializeEvent($config, $enabled);
		$this->eventDispatcher->dispatch(Events::INITIALZE, $event);
	}


	/**
	 *
	 *
	 * @param \PageModel   $page
	 * @param \LayoutModel $layout
	 */
	public function initializeLayout($page, \LayoutModel $layout)
	{
		$environment = Environment::getInstance();
		$environment->setEnabled($layout->layoutType == 'bootstrap');
	}


	/**
	 * select an icon se
	 */
	protected function selectIconSet()
	{
		$config   = Bootstrap::getConfig();
		$iconSet  = Bootstrap::getIconSet();
		$active   = $config->get('icons.active');
		$template = $config->get(sprintf('icons.sets.%s.template', $active));
		$path     = $config->get(sprintf('icons.sets.%s.path', $active));

		if($active) {
			if($path && file_exists(TL_ROOT . '/' . $path)) {
				$icons = include TL_ROOT . '/' . $path;
				$iconSet
					->setIconSetName($active)
					->setIcons($icons)
					->setTemplate($template);
			}
		}
	}


	/**
	 * Load dynamic templates
	 */
	protected function loadDynamicTemplates()
	{
		$environment = Environment::getInstance();
		$config      = $environment->getConfig();

		// load dynamic templates
		$event = new LoadDynamicTemplatesEvent($config);
		$this->eventDispatcher->dispatch(Events::AUTOLOAD_TEMPLATES, $event);

		\TemplateLoader::addFiles($event->getTemplates()->getArrayCopy());
	}

} 