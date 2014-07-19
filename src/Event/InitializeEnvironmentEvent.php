<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\EventDispatcher\Event;

final class InitializeEnvironmentEvent extends Event
{

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var bool
	 */
	protected $enabled;


	/**
	 * @param $config
	 * @param $enabled
	 */
	function __construct(Config $config, $enabled)
	{
		$this->config  = $config;
		$this->enabled = $enabled;
	}


	/**
	 * @param \Netzmacht\Bootstrap\Core\Config $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}


	/**
	 * @return \Netzmacht\Bootstrap\Core\Config
	 */
	public function getConfig()
	{
		return $this->config;
	}


	/**
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}


	/**
	 * @return boolean
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

} 