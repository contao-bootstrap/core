<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Event;


use Netzmacht\Bootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

final class InitializeLayoutEvent extends Event
{
	const NAME = 'bootstrap.initialize-layout';

	/**
	 * @var Environment
	 */
	private $environment;

	/**
	 * @var \LayoutModel
	 */
	private $layoutModel;

	/**
	 * @var \PageModel
	 */
	private $pageModel;


	/**
	 * @param Environment $environment
	 * @param \LayoutModel $layoutModel
	 * @param \PageModel $pageModel
	 */
	function __construct(Environment $environment, \LayoutModel $layoutModel, \PageModel $pageModel)
	{
		$this->environment = $environment;
		$this->layoutModel = $layoutModel;
		$this->pageModel   = $pageModel;
	}


	/**
	 * @return \Netzmacht\Bootstrap\Core\Environment
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}


	/**
	 * @return \LayoutModel
	 */
	public function getLayoutModel()
	{
		return $this->layoutModel;
	}


	/**
	 * @return \PageModel
	 */
	public function getPageModel()
	{
		return $this->pageModel;
	}

} 