<?php

namespace Netzmacht\Bootstrap\Core;

/**
 * Class Config
 * @package Netzmacht\Bootstrap
 */
class Config
{

	/**
	 * @var array
	 */
	protected $config = array();


	/**
	 * @param array $config
	 */
	function __construct(array $config=array())
	{
		$this->config = $config;
	}


	/**
	 * @param $key
	 * @param $default
	 * @return mixed
	 */
	public function get($key, $default=null)
	{
		$chunks = explode('.', $key);
		$value  = $this->config;

		while (($chunk = array_shift($chunks)) !== null) {
			if (!array_key_exists($chunk, $value)) {
				return $default;
			}

			$value = $value[$chunk];
		}

		return $value;
	}


	/**
	 * @param $key
	 * @param $value
	 * @return $this
	 */
	public function set($key, $value)
	{
		$chunks = explode('.', $key);
		$name	= array_pop($chunks);
		$config = &$this->config;

		foreach($chunks as $chunk) {
			if (!array_key_exists($chunk, $config)) {
				$config[$chunk] = array();
			}

			$config = &$config[$chunk];
		}

		$config[$name] = $value;

		return $this;
	}


	/**
	 * @param $key
	 * @return bool
	 */
	public function has($key)
	{
		$chunks = explode('.', $key);
		$value  = $this->config;

		while (($chunk = array_shift($chunks)) !== null) {
			if (!array_key_exists($chunk, $value)) {
				return false;
			}

			$value = $value[$chunk];
		}

		return true;
	}


	/**
	 * @param $data
	 * @return $this
	 */
	public function import(array $data)
	{
		$this->config = array_merge_recursive($this->config, $data);

		return $this;
	}

} 