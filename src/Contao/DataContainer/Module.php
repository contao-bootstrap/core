<?php

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;


class Module
{
	/**
	 * get all templates. A templatePrefix can be defined using eval.templatePrefix
	 *
	 * @param $dc
	 *
	 * @return array
	 */
	public function getTemplates($dc)
	{
		$config = array();
		$prefix = '';
		$key    = null;

		// MCW compatibility
		if($dc instanceof \MultiColumnWizard) {
			$field = $dc->strField;
			$table = $dc->strTable;
		}
		else {
			$field = $dc->field;
			$table = $dc->table;
		}

		if(array_key_exists('eval', $GLOBALS['TL_DCA'][$table]['fields'][$field])) {
			$config = $GLOBALS['TL_DCA'][$table]['fields'][$field]['eval'];
		}

		if(array_key_exists('templatePrefix', $config)) {
			$prefix = $config['templatePrefix'];
		}

		if(array_key_exists('templateThemeId', $config)) {
			$key = $config['templateThemeId'];
		}

		$key = $key == '' ? null : $dc->activeRecord->$key;

		return \Controller::getTemplateGroup($prefix, $key);
	}


	/**
	 * @param \DataContainer $dc
	 *
	 * @return string
	 */
	public function pagePicker(\DataContainer $dc)
	{
		return sprintf(
			' <a href="contao/page.php?do=%s&amp;table=%s&amp;field=%s&amp;value=%s" title="%s" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'%s\',\'url\':this.href,\'id\':\'%s\',\'tag\':\'ctrl_%s\',\'self\':this});return false">%s</a>',
			\Input::get('do'),
			$dc->table,
			$dc->field,
			str_replace(array('{{link_url::', '}}'), '', $dc->value),
			specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']),
			specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])),
			$dc->field,
			$dc->field . ((\Input::get('act') == 'editAll') ? '_' . $dc->id : ''),
			\Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"')
		);
	}

} 