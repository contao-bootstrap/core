<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Contao\Template;

use Netzmacht\Bootstrap\Core\Bootstrap;

/**
 * Class TemplateModifier contains all template modifiers used by bootstrap config
 * @package Netzmacht\Bootstrap
 */
class Modifier
{

	/**
	 * execute all registered template modifiers
	 *
	 * @param \Template $template
	 */
	public function execute(\Template $template)
	{
		if(!Bootstrap::isEnabled()) {
			return;
		}

		foreach((array) Bootstrap::getConfigVar('templates', 'modifiers') as $config) {
			if($config['disabled'] || !$this->isTemplateAffected($template->getName(), (array) $config['templates'])) {
				continue;
			}

			if($config['type'] == 'replace') {
				if(is_callable($config['replace'])) {
					$value = call_user_func($config['replace'], $template);
				}
				else {
					$value = $config['replace'];
				}

				$template->$config['key'] = str_replace($config['search'], $value, $template->$config['key']);
			}
			elseif($config['type'] == 'callback') {
				call_user_func($config['callback'], $template);
			}
		}
	}


	/**
	 * @param $buffer
	 * @param $templateName
	 * @return mixed
	 */
	public function parse($buffer, $templateName)
	{
		if(!Bootstrap::isEnabled()) {
			return $buffer;
		}

		foreach((array) Bootstrap::getConfigVar('templates', 'parsers') as $config)
		{
			if($config['disabled'] || !$this->isTemplateAffected($templateName, (array) $config['templates'])) {
				continue;
			}

			if($config['type'] == 'replace') {
				if(is_callable($config['replace'])) {
					$value = call_user_func($config['replace'], $buffer, $templateName);
				}
				else {
					$value = $config['replace'];
				}

				$buffer = str_replace($config['search'], $value, $buffer);
			}
			elseif($config['type'] == 'callback') {
				$buffer = call_user_func($config['callback'], $buffer, $templateName);
			}
		}

		return $buffer;
	}


	/**
	 * @param $template
	 * @param $templates
	 * @return bool
	 */
	protected function isTemplateAffected($template, $templates)
	{
		foreach($templates as $config) {
			if($template == $config) {
				return true;
			}
			elseif(substr($config, -1) == '*' && 0 == strcasecmp(substr($config, 0, -1), substr($template, 0, strlen($config) -1))) {
				return true;
			}

		}

		return false;
	}

}