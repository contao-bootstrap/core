<?php

namespace Netzmacht\Bootstrap\Core;

use Netzmacht\Bootstrap\Core\Event\Events;
use Netzmacht\Bootstrap\Core\Event\InitializeEvent;
use Netzmacht\Bootstrap\Core\Event\LoadDynamicTemplatesEvent;
use Netzmacht\Bootstrap\Core\Event\SelectIconSetEvent;
use Netzmacht\Bootstrap\Core\Helper\Icons;
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
	 * @var Icons
	 */
	protected $icons;


	/**
	 * @param Config $config
	 * @param Icons $icons
	 * @param EventDispatcherInterface $eventDispatcher
	 */
	function __construct(Config $config, Icons $icons, EventDispatcherInterface $eventDispatcher)
	{
		$this->config          = $config;
		$this->icons           = $icons;
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
	 * @return Icons
	 */
	public function getIcons()
	{
		return $this->icons;
	}


	/**
	 * @return mixed
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