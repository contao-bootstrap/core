<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\EventDispatcher\Event;

class LoadDynamicTemplatesEvent extends Event
{

	/**
	 * @var \ArrayObject
	 */
	protected $templates;

	/**
	 * @var Config
	 */
	protected $config;


	/**
	 * @param Config $config
	 * @param array $templates
	 */
	function __construct(Config $config, array $templates=array())
	{
		$this->config = $config;
		$this->setTemplates($templates);
	}


	/**
	 * @return \Netzmacht\Bootstrap\Core\Config
	 */
	public function getConfig()
	{
		return $this->config;
	}


	/**
	 * @param array|\ArrayObject $templates
	 * @return $this
	 */
	public function setTemplates($templates)
	{
		if(is_array($templates)) {
			$templates = new \ArrayObject($templates);
		}

		$this->templates = $templates;

		return $this;
	}


	/**
	 * @return \ArrayObject
	 */
	public function getTemplates()
	{
		return $this->templates;
	}


	/**
	 * @param $name
	 * @param $path
	 * @return $this
	 */
	public function addTemplate($name, $path)
	{
		$this->templates[$name] = $path;
	}

} 