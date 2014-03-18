<?php


namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Contao\ContentElement\Wrapper\Helper;

/**
 * Class WrapperDataContainer
 *
 * @package Netzmacht\Bootstrap\DataContainer
 */
class Wrapper
{

	/**
	 * @var int
	 */
	const TRIGGER_CREATE = 1;

	/**
	 * @var int
	 */
	const TRIGGER_DELETE = 2;

	/**
	 * order const ascending
	 */
	const ORDER_ASC = 'asc';

	/**
	 * order const descending
	 */
	const ORDER_DESC = 'desc';

	/**
	 * @var Helper
	 */
	protected $wrapper;


	/**
	 * Try to create wrapper elements, triggered by save_callback of type field
	 * @param $value
	 * @param $dc
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function save($value, $dc)
	{
		$start   = Helper::TYPE_START;
		$stop    = Helper::TYPE_STOP;
		$sep     = Helper::TYPE_SEPARATOR;
		$record  = $dc->activeRecord;
		$sorting = $record->sorting;

		// try to create wrapper helper. will throw an exception if an unknown type is selected
		try {
			$wrapper = Helper::create($record);
			$this->wrapper = $wrapper;
		}
		catch(\Exception $e) {
			return $value;
		}

		$set = array();


		// check for existing parent element and try to create it if not existing
		if($wrapper->isTypeOf($start)) {
			if($record->bootstrap_parentId == '') {
				$parent = $wrapper->findPreviousElement($start);

				if($parent) {
					// set relation to parent element
					$set['bootstrap_parentId'] = $parent->id;

					$end = $wrapper->findRelatedElement($record, $stop);

					if($end === null) {
						$set['sorting'] = $parent->sorting + 2;
					}
					elseif($parent !== null && $parent->sorting > $end->sorting) {
						$set['sorting'] = $end->sorting - 2;
					}

					// set relation to parent element
					if(!$wrapper->isTypeOf($start)) {
						$set['sorting'] = $parent->id;
					}

					\Database::getInstance()
						->prepare('UPDATE tl_content %s WHERE id=?')
						->set($set)
						->execute($record->id);
				}

				// create parent if possible
				elseif($this->isTrigger($wrapper->getType(), $start)) {
					$sorting = $sorting-2;
					$this->createElement($record, $sorting, $start);
				}

				// no parent element exists, throw error
				else {
					throw new \Exception(sprintf(
						$GLOBALS['TL_LANG']['ERR']['wrapperStartNotExists'],
						$GLOBALS['TL_LANG']['CTE'][$value][0] ?: $value
					));
				}
			}
		}

		// create separators if possible
		if(!$wrapper->isTypeOf($sep) &&
			($this->isTrigger($wrapper->getType(), $sep) ||
				$this->isTrigger($wrapper->getType(), $sep, static::TRIGGER_DELETE))
		) {
			$config = Bootstrap::getConfigVar(sprintf('wrappers.%s.%s', $wrapper->getGroup(), $sep));

			$callback = $config['countExisting'];
			$instance = \Controller::importStatic($callback[0]);
			$existing = $instance->$callback[1]($record);

			$callback = $config['countRequired'];
			$instance = \Controller::importStatic($callback[0]);
			$required = $instance->$callback[1]($record);

			if($existing < $required) {
				if($this->isTrigger($wrapper->getType(), $sep)) {
					$count = $required - $existing;

					for($i = 0; $i < $count; $i++) {
						$this->createElement($record, $sorting, $sep);
					}

					$end = $wrapper->findRelatedElement($stop);

					if($end && $end->sorting <= $sorting) {
						$sorting = $sorting + 2;
						$end->sorting = $sorting;
						$end->save();
					}
				}
			}
			elseif($required < $existing) {
				if($this->isTrigger($wrapper->getType(), $sep, static::TRIGGER_DELETE)) {
					$count    = $existing - $required;
					$parentId = $wrapper->isTypeOf($start)
						? $record->id
						: $record->bootstrap_parentId;

					\Database::getInstance()
						->prepare('DELETE FROM tl_content WHERE bootstrap_parentId=? AND type=? ORDER BY sorting DESC')
						->limit($count)
						->execute($parentId, $wrapper->getTypeName($sep));
				}
			}
		}

		// cereate end element
		if($wrapper->isTypeOf($start) && $this->isTrigger($wrapper->getType(), $stop)) {
			$end = $wrapper->countRelatedElements($record, $stop);

			if(!$end) {
				$this->createElement($record, $sorting, $stop);
			}
		}

		return $value;
	}


	/**
	 * handle content element deletion, called by ondelete_callback
	 * @param $dc
	 */
	public function delete($dc)
	{
		$model = \ContentModel::findByPk($dc->id);

		try {
			$wrapper = Helper::create($model);
		}
		catch(\Exception $e) {
			return;
		}

		if($wrapper->isTypeOf(Helper::TYPE_START)) {
			$deleteTypes = array();

			if($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR, static::TRIGGER_DELETE)) {
				$deleteTypes[] = $wrapper->getTypeName(Helper::TYPE_SEPARATOR);
			}

			if($this->isTrigger($wrapper->getType(), Helper::TYPE_STOP, static::TRIGGER_DELETE)) {
				$deleteTypes[] = $wrapper->getTypeName(Helper::TYPE_STOP);
			}

			if(!empty($deleteTypes)) {
				\Database::getInstance()
					->prepare(sprintf(
						'DELETE FROM tl_content WHERE bootstrap_parentId=? AND type IN(\'%s\')',
						implode('\',\'', $deleteTypes)
					))
					->execute($model->id);
			}
		}
		elseif($wrapper->isTypeOf(Helper::TYPE_STOP)) {
			if($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR, static::TRIGGER_DELETE)) {
				\Database::getInstance()
					->prepare('DELETE FROM tl_content WHERE bootstrap_parentId=? AND type=?')
					->execute(
						$model->bootstrap_parentId,
						$wrapper->getTypeName(Helper::TYPE_SEPARATOR)
					);
			}

			if($this->isTrigger($wrapper->getType(), Helper::TYPE_START, static::TRIGGER_DELETE)) {
				$query = sprintf('DELETE FROM %s WHERE id=?', $model->getTable());

				\Database::getInstance()
					->prepare($query)
					->execute($model->bootstrap_parentId);
			}
		}
		else {
			// todo: handle seperator delete actions
		}
	}


	/**
	 * Create a new wrapper element
	 *
	 * @param $parent
	 * @param int                 $sorting
	 * @param string              $type
	 *
	 * @return \ContentModel
	 */
	protected function createElement($parent, &$sorting, $type=Helper::TYPE_SEPARATOR)
	{
		$model = new \ContentModel();

		if($type == Helper::TYPE_START) {
			$sorting = $sorting - 2;
		}
		else {
			$sorting = $sorting + 2;
			$model->bootstrap_parentId = $parent->id;
		}

		$model->tstamp  = time();
		$model->pid     = $parent->pid;
		$model->ptable  = $parent->ptable;
		$model->type    = $this->wrapper->getTypeName($type);
		$model->sorting = $sorting;

		$model->save();

		return $model;
	}


	/**
	 * check if action can be triggered
	 *
	 * @param string $trigger
	 * @param string $target
	 * @param int    $action
	 *
	 * @return bool
	 */
	protected function isTrigger($trigger, $target, $action = Wrapper::TRIGGER_CREATE)
	{
		$config = Bootstrap::getConfigVar(sprintf('wrappers.%s', $this->wrapper->getGroup()));
		$key    = $action == static::TRIGGER_DELETE ? 'triggerDelete' : 'triggerCreate';

		if(isset($config[$trigger][$key]) && $config[$trigger][$key]) {
			$key = $action == static::TRIGGER_DELETE ? 'autoDelete' : 'autoCreate';

			// check if count callback is defined
			if($target == Helper::TYPE_SEPARATOR) {
				if(!isset($config[$target]['countExisting']) || !isset($config[$target]['countRequired'])) {
					return false;
				}
			}

			return(isset($config[$target][$key]) && $config[$target][$key]);
		}

		return false;
	}

}