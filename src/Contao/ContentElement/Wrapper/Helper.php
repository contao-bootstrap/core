<?php

namespace Netzmacht\Bootstrap\Core\Contao\ContentElement\Wrapper;


use Netzmacht\Bootstrap\Core\Bootstrap;

class Helper
{
	const TYPE_START     = 'start';

	const TYPE_STOP      = 'stop';

	const TYPE_SEPARATOR = 'separator';

	/**
	 * @var \Database\Result|\Model
	 */
	protected $model;

	protected $type;

	protected $group;

	/**
	 * @var \Database
	 */
	protected $database;


	/**
	 * @param \Model|\Database\Result $model
	 * @param string $type
	 * @param string $group
	 * @param \Database $database
	 */
	public function __construct($model, $type, $group, \Database $database)
	{
		$this->model = $model;
		$this->type  = $type;
		$this->group = $group;
		$this->database = $database;
	}


	/**
	 * @param \Model|\Database\Result $model
	 * @throws \Exception
	 * @return static
	 */
	public static function create($model)
	{
		$wrappers = Bootstrap::getConfigVar('wrappers');
		foreach($wrappers as $groupName => $group) {
			foreach($group as $type => $config) {
				if($config['name'] == $model->type) {
					return new static($model, $type, $groupName, \Database::getInstance());
				}
			}
		}

		throw new \Exception(sprintf('Unknown wrapper type "%s"', $model->type));
	}


	/**
	 * @param $type
	 * @return bool
	 */
	public function isTypeOf($type)
	{
		return $this->getType() == $type;
	}


	/**
	 * @return string
	 */
	public function getGroup()
	{
		return $this->group;
	}


	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}


	/**
	 * @param string $type
	 * @return string
	 */
	public function getTypeName($type=null)
	{
		$group = $this->getGroup();
		$type  = $type === null ? $this->getType() : $type;
		$path  = sprintf('wrappers.%s.%s.name', $group, $type);

		return Bootstrap::getConfigVar($path);
	}


	/**
	 * Count related elements
	 * 
	 * @param null $type
	 * @return int
	 */
	public function countRelatedElements($type=null)
	{
		$model = $this->model;

		if($this->isTypeOf(static::TYPE_START)) {
			if($type === null) {
				$column = array('bootstrap_parentId=?');
				$values = array($model->id);
			}
			else {
				$column = array('bootstrap_parentId=?', 'type=?');
				$values = array($model->id, $this->getTypeName($type));
			}
		}
		elseif($type === null) {
			$column = array('id !=?', '(bootstrap_parentId=? OR id=?)');
			$values = array($model->id, $model->bootstrap_parentId, $model->bootstrap_parentId);
		}
		elseif($type == static::TYPE_START) {
			$column = array('id=?');
			$values = array($model->bootstrap_parentId);
		}
		else {
			$column = array('id !=?', 'bootstrap_parentId=?', 'type=?');
			$values = array($model->id, $model->bootstrap_parentId, $this->getTypeName($type));

		}

		$column[] = 'invisible=?';
		$values[] = '';

		return \ContentModel::countBy($column, $values);
	}


	/**
	 * try to find previous element of same type or specified type
	 *
	 * @param null $type
	 *
	 * @return \Model|null
	 */
	public function findPreviousElement($type=null)
	{
		if($type === null) {
			$type = $this->getType();
		}

		$column = array('pid=?', 'ptable=?', 'type=?', 'sorting<?');
		$values = array($this->model->pid, $this->model->ptable, $this->getTypeName($type), $this->model->sorting);

		return \ContentModel::findOneBy($column, $values);
	}

	/**
	 * Find related elements of a start element
	 *
	 * @param null $type
	 *
	 * @return \ContentModel|null
	 */
	public function findRelatedElement($type=null)
	{
		$model = $this->model;

		// invalid call
		if($model->bootstrap_parentId == '' && !$this->isTypeOf(static::TYPE_START)) {
			// TODO: throw exception?
			return null;
		}

		$options = array(
			'order' => 'sorting'
		);

		if($type === null) {
			$column   = 'bootstrap_parentId';
			$values[] = $model->id;
		}
		else {
			$column   = array('bootstrap_parentId=? ', 'type=?');
			$values[] = $model->id;
			$values[] = $this->getTypeName($type);
		}

		return \ContentModel::findOneBy($column, $values, $options);
	}


} 