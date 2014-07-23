<?php

namespace Netzmacht\Bootstrap\Core;
use Netzmacht\Bootstrap\Core\Util\ArrayUtil;

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
	 * @return $this
	 */
	public function remove($key)
	{
		$chunks = explode('.', $key);
		$name	= array_pop($chunks);
		$config = &$this->config;

		foreach($chunks as $chunk) {
			if (!array_key_exists($chunk, $config)) {
				return $this;
			}

			$config = &$config[$chunk];
		}

		unset($config[$name]);

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
	 * @param array $data
	 * @param null $path
	 * @return $this
	 */
	public function merge(array $data, $path=null)
	{
		if($path) {
			$config = (array) $this->get($path);
			$config = ArrayUtil::merge($config, $data);

			$this->set($path, $config);
		}
		else {
			$this->config = ArrayUtil::merge($this->config, $data);
		}

		return $this;
	}


	/**
	 * @param $file
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public function import($file)
	{
		if(!file_exists($file)) {
			throw new \InvalidArgumentException(sprintf('File "%s" not found', $file));
		}

		$config = include $file;

		if(!is_array($config)) {
			throw new \RuntimeException('Loaded config is not an array');
		}

		$this->merge($config);
	}

} 