<?php

namespace Netzmacht\Bootstrap\Core;

use Netzmacht\Bootstrap\Core\Helper\IconSet;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class Environment
{
	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDispatcher;

	/**
	 * @var bool
	 */
	protected $enabled = false;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var IconSet
	 */
	protected $iconSet;


	/**
	 * @param Config $config
	 * @param IconSet $iconSet
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	function __construct(Config $config, IconSet $iconSet, EventDispatcherInterface $eventDispatcher)
	{
		$this->config          = $config;
		$this->iconSet         = $iconSet;
		$this->eventDispatcher = $eventDispatcher;
	}


	/**
	 * @return Environment
	 */
	public static function getInstance()
	{
		return $GLOBALS['container']['bootstrap.environment'];
	}


	/**
	 * @return IconSet
	 */
	public function getIconSet()
	{
		return $this->iconSet;
	}


	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}


	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}


	/**
	 * @param bool $enabled
	 * @return $this
	 */
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;

		return $this;
	}

} 