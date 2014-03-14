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

} 