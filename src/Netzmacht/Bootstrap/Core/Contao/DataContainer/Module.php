<?php

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;


class Module
{
	/**
	 * Get all modules prepared for select wizard
	 *
	 * @return array
	 */
	public function getAllModules()
	{
		$modules = array();
		$query   = 'SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id';

		if(\Input::get('table') == 'tl_module' && \Input::get('act') == 'edit') {
			$query .= ' WHERE m.id != ?';
		}

		$query .= ' ORDER BY t.name, m.name';
		$result = \Database::getInstance()
			->prepare($query)
			->execute(\Input::get('id'));

		while ($result->next()) {
			$modules[$result->theme][$result->id] = $result->name . ' (ID ' . $result->id . ')';
		}

		return $modules;
	}


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

} 