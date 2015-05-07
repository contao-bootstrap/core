<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\ContentElement\Wrapper;

use Netzmacht\Bootstrap\Core\Bootstrap;

/**
 * Class Helper for wrapper elements.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\ContentElement\Wrapper
 */
class Helper
{
    const TYPE_START = 'start';

    const TYPE_STOP = 'stop';

    const TYPE_SEPARATOR = 'separator';

    /**
     * Model if the current wrapper element.
     *
     * @var \Database\Result|\Model
     */
    protected $model;

    /**
     * Type of wrapper.
     *
     * @var string
     */
    protected $type;

    /**
     * Group value.
     *
     * @var string
     */
    protected $group;

    /**
     * Database connection.
     *
     * @var \Database
     */
    protected $database;

    /**
     * Construct.
     *
     * @param \Model|\Database\Result $model    The wrapper model.
     * @param string                  $type     Type of the wrapper.
     * @param string                  $group    Group element contains to.
     * @param \Database               $database The database connection.
     */
    public function __construct($model, $type, $group, \Database $database)
    {
        $this->model    = $model;
        $this->type     = $type;
        $this->group    = $group;
        $this->database = $database;
    }

    /**
     * Create the helper.
     *
     * @param \Model|\Database\Result $model Current wrapper element.
     *
     * @throws \Exception If element is not a configured wrapper.
     *
     * @return static
     */
    public static function create($model)
    {
        $wrappers = Bootstrap::getConfigVar('wrappers', array());
        foreach ($wrappers as $groupName => $group) {
            foreach ($group as $type => $config) {
                if ($config['name'] == $model->type) {
                    return new static($model, $type, $groupName, \Database::getInstance());
                }
            }
        }

        throw new \Exception(sprintf('Unknown wrapper type "%s"', $model->type));
    }

    /**
     * Consider if wrapper is a specific type.
     *
     * @param string $type Type name.
     *
     * @return bool
     */
    public function isTypeOf($type)
    {
        return $this->getType() == $type;
    }

    /**
     * Get group value.
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get type value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get full type name.
     *
     * @param string $type Type name.
     *
     * @return string
     */
    public function getTypeName($type = null)
    {
        $group = $this->getGroup();
        $type  = $type === null ? $this->getType() : $type;
        $path  = sprintf('wrappers.%s.%s.name', $group, $type);

        return Bootstrap::getConfigVar($path);
    }

    /**
     * Count related elements.
     *
     * @param string|null $type Name of the type. If empty current type is used.
     *
     * @return int
     */
    public function countRelatedElements($type = null)
    {
        $model = $this->model;

        if ($this->isTypeOf(static::TYPE_START)) {
            if ($type === null) {
                $column = array('bootstrap_parentId=?');
                $values = array($model->id);
            } else {
                $column = array('bootstrap_parentId=?', 'type=?');
                $values = array($model->id, $this->getTypeName($type));
            }
        } elseif ($type === null) {
            $column = array('id !=?', '(bootstrap_parentId=? OR id=?)');
            $values = array($model->id, $model->bootstrap_parentId, $model->bootstrap_parentId);
        } elseif ($type == static::TYPE_START) {
            $column = array('id=?');
            $values = array($model->bootstrap_parentId);
        } else {
            $column = array('id !=?', 'bootstrap_parentId=?', 'type=?');
            $values = array($model->id, $model->bootstrap_parentId, $this->getTypeName($type));

        }

        $column[] = 'invisible=?';
        $values[] = '';

        return \ContentModel::countBy($column, $values);
    }

    /**
     * Try to find previous element of same type or specified type.
     *
     * @param string|null $type Name of the type. If empty current type is used.
     *
     * @return \Model|null
     */
    public function findPreviousElement($type = null)
    {
        if ($type === null) {
            $type = $this->getType();
        }

        $column = array('pid=?', 'ptable=?', 'type=?', 'sorting<?');
        $values = array($this->model->pid, $this->model->ptable, $this->getTypeName($type), $this->model->sorting);

        return \ContentModel::findOneBy($column, $values, array('order' => 'sorting DESC'));
    }

    /**
     * Find related elements of a start element.
     *
     * @param string|null $type Name of the type. If empty current type is used.
     *
     * @return \ContentModel|null
     */
    public function findRelatedElement($type = null)
    {
        $model = $this->model;

        // invalid call
        if ($model->bootstrap_parentId == '' && !$this->isTypeOf(static::TYPE_START)) {
            return null;
        }

        $options = array(
            'order' => 'sorting'
        );

        if ($type === null) {
            $column   = 'bootstrap_parentId';
            $values[] = $model->id;
        } else {
            $column   = array('bootstrap_parentId=? ', 'type=?');
            $values[] = $model->id;
            $values[] = $this->getTypeName($type);
        }

        return \ContentModel::findOneBy($column, $values, $options);
    }
}
