<?php

namespace Netzmacht\Bootstrap\Core\Contao;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Environment;
use Netzmacht\Bootstrap\Core\Event\InitializeEnvironmentEvent;
use Netzmacht\Bootstrap\Core\Event\InitializeLayoutEvent;
use Netzmacht\Bootstrap\Core\Event\ReplaceInsertTagsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Hooks
{

	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

	/**
	 * @var Environment
	 */
	protected $environment;


	/**
	 * Construct
	 */
	function __construct()
	{
		$this->eventDispatcher = $GLOBALS['container']['event-dispatcher'];
		$this->environment     = $GLOBALS['container']['bootstrap.environment'];
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
		$event  = new ReplaceInsertTagsEvent($tag, $params, $cache);

		$this->eventDispatcher->dispatch($event::NAME, $event);

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
		$event = new InitializeEnvironmentEvent($this->environment);
		$this->eventDispatcher->dispatch($event::NAME, $event);
	}


	/**
	 * Initialize Layout
	 *
	 * @param \PageModel   $page
	 * @param \LayoutModel $layout
	 */
	public function initializeLayout(\PageModel $page, \LayoutModel $layout)
	{
		$environment = $this->environment;
		$environment->setLayout($layout);
		$environment->setEnabled($layout->layoutType == 'bootstrap');

		$event = new InitializeLayoutEvent($environment, $layout, $page);
		$this->eventDispatcher->dispatch($event::NAME, $event);
	}

    /**
     * Add icon stylesheet to the backend template.
     *
     * @param \Template $template
     */
    public function addIconStylesheet(\Template $template)
    {
        if (TL_MODE != 'BE' || $template->getName() != 'be_main') {
            return;
        }

        $active = Bootstrap::getConfigVar('icons.active');

        if ($active) {
            $css = Bootstrap::getConfigVar(sprintf('icons.sets.%s.stylesheet', $active));
            $stylesheets = $template->stylesheets;

            foreach ((array)$css as $file) {
                $stylesheets .= sprintf('<link rel="stylesheet" href="%s">', $file);
            }

            $template->stylesheets = $stylesheets;
        }
    }

	/**
	 * select an icon set
	 */
	protected function selectIconSet()
	{
		$config   = Bootstrap::getConfig();
		$iconSet  = Bootstrap::getIconSet();
		$active   = $config->get('icons.active');
		$template = $config->get(sprintf('icons.sets.%s.template', $active));
        $path     = $config->get(sprintf('icons.sets.%s.path', $active));

        if( $active && $path && file_exists(TL_ROOT . '/' . $path)) {
            $icons = include TL_ROOT . '/' . $path;
            $iconSet
                ->setIconSetName($active)
                ->setIcons($icons)
                ->setTemplate($template);
        }
	}
} 