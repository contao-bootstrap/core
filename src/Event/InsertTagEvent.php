<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Symfony\Component\EventDispatcher\Event;

class InsertTagEvent extends Event
{

	/**
	 * @var string
	 */
	protected $html;

	/**
	 * @var string
	 */
	protected $tag;

	/**
	 * @var bool
	 */
	protected $cached;

	/**
	 * @var array
	 */
	protected $params = array();


	/**
	 * @param $tag
	 * @param array $params
	 * @param bool $cached
	 */
	function __construct($tag, array $params=array(), $cached=true)
	{
		$this->tag    = $tag;
		$this->params = $params;
		$this->cached = $cached;
	}


	/**
	 * @param string $html
	 * @return $this
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getHtml()
	{
		return $this->html;
	}


	/**
	 * @param array $params
	 * @return $this
	 */
	public function setParams($params)
	{
		$this->params = $params;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}


	/**
	 * @param $index
	 * @return string|null
	 */
	public function getParam($index)
	{
		if(isset($this->params[$index])) {
			return $this->params[$index];
		}

		return null;
	}


	/**
	 * @param $index
	 * @param $value
	 * @return $this
	 */
	public function setParam($index, $value)
	{
		$this->params[$index] = $value;

		return $this;
	}


	/**
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}


	/**
	 * @return bool
	 */
	public function isCached()
	{
		return $this->cached;
	}

} 