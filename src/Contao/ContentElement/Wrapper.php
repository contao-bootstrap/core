<?php

namespace Netzmacht\Bootstrap\Core\Contao\ContentElement;

abstract class Wrapper extends \Module
{
	/**
	 * @var Wrapper\Helper
	 */
	protected $wrapper;

	/**
	 * @var string
	 */
	protected static $wrapperName = 'tabs';


	/**
	 * load start element
	 *
	 * @param $objElement
	 */
	public function __construct($objElement)
	{
		parent::__construct($objElement);

		$this->wrapper = Wrapper\Helper::create($objElement);
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		// backend mode
		if(TL_MODE == 'BE')
		{
			if($this->wrapper->isTypeOf(Wrapper\Helper::TYPE_STOP)) {
				return '';
			}

			// do not display title if element is a wrapper element
			if(version_compare(VERSION, '3.1', '<')) {
				return sprintf('<strong class="title">%s</strong>', $this->type);
			}

			return '';
		}

		$this->wrapperType = $this->wrapper->getType();

		return parent::generate();
	}
} 