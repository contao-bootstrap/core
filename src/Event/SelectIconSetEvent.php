<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\EventDispatcher\Event;

class SelectIconSetEvent extends Event
{

	/**
	 * @var \Netzmacht\Bootstrap\Core\Config
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @var string
	 */
	protected $iconSetName;

	/**
	 * @var array
	 */
	protected $icons = array();


	/**
	 * @param \Netzmacht\Bootstrap\Core\Config $config
	 */
	function __construct(Config $config)
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
	 * @param string $iconSetName
	 * @return $this
	 */
	public function setIconSetName($iconSetName)
	{
		$this->iconSetName = $iconSetName;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getIconSetName()
	{
		return $this->iconSetName;
	}


	/**
	 * @param array $icons
	 * @return $this
	 */
	public function setIcons($icons)
	{
		$this->icons = $icons;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getIcons()
	{
		return $this->icons;
	}


	/**
	 * @param string $template
	 * @return $this
	 */
	public function setTemplate($template)
	{
		$this->template = $template;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

} 