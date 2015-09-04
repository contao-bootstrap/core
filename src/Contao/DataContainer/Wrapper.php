<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Bit3\Contao\MetaPalettes\MetaPalettes;
use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Contao\ContentElement\Wrapper\Helper;

/**
 * Class WrapperDataContainer handles wrapper content elements in the backend configuration.
 *
 * @package Netzmacht\Bootstrap\DataContainer
 */
class Wrapper
{
    const TRIGGER_CREATE = 'trigger-create';

    const TRIGGER_DELETE = 'trigger-delete';

    const ORDER_ASC = 'asc';

    const ORDER_DESC = 'desc';

    /**
     * The wrapper helper.
     *
     * @var Helper
     */
    protected $wrapper;

    /**
     * Try to create wrapper elements, triggered by save_callback of type field.
     *
     * @param mixed          $value         Mixed value.
     * @param \DataContainer $dataContainer Data container.
     *
     * @return mixed
     *
     * @throws \Exception If something went wrong.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function save($value, $dataContainer)
    {
        $start   = Helper::TYPE_START;
        $stop    = Helper::TYPE_STOP;
        $sep     = Helper::TYPE_SEPARATOR;
        $record  = $dataContainer->activeRecord;
        $sorting = $record->sorting;

        // try to create wrapper helper. will throw an exception if an unknown type is selected
        try {
            $wrapper       = Helper::create($record);
            $this->wrapper = $wrapper;
        } catch (\Exception $e) {
            return $value;
        }

        $set = array();

        // check for existing parent element and try to create it if not existing
        if (!$wrapper->isTypeOf($start)) {
            $sorting = $this->saveStartType($value, $record, $wrapper, $set, $sorting);
        }

        // create separators if possible
        if (!$wrapper->isTypeOf($sep)
            && ($this->isTrigger($wrapper->getType(), $sep)
                || $this->isTrigger($wrapper->getType(), $sep, static::TRIGGER_CREATE))
        ) {
            $sorting = $this->createSeparators($wrapper, $record, $sorting);
        }

        // cereate end element
        if ($wrapper->isTypeOf($start) && $this->isTrigger($wrapper->getType(), $stop)) {
            $end = $wrapper->countRelatedElements($stop);

            if (!$end) {
                $this->createElement($record, $sorting, $stop);
            }
        }

        return $value;
    }

    /**
     * Handle content element deletion, called by ondelete_callback.
     *
     * @param \DataContainer $dataContainer The data container driver.
     *
     * @return void
     */
    public function delete($dataContainer)
    {
        $model = \ContentModel::findByPk($dataContainer->id);

        try {
            $wrapper       = Helper::create($model);
            $this->wrapper = $wrapper;
        } catch (\Exception $e) {
            return;
        }

        if ($wrapper->isTypeOf(Helper::TYPE_START)) {
            $deleteTypes = array();

            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR, static::TRIGGER_DELETE)) {
                $deleteTypes[] = $wrapper->getTypeName(Helper::TYPE_SEPARATOR);
            }

            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_STOP, static::TRIGGER_DELETE)) {
                $deleteTypes[] = $wrapper->getTypeName(Helper::TYPE_STOP);
            }

            if (!empty($deleteTypes)) {
                \Database::getInstance()
                    ->prepare(sprintf(
                        'DELETE FROM tl_content WHERE bootstrap_parentId=? AND type IN(\'%s\')',
                        implode('\',\'', $deleteTypes)
                    ))
                    ->execute($model->id);
            }
        } elseif ($wrapper->isTypeOf(Helper::TYPE_STOP)) {
            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR, static::TRIGGER_DELETE)) {
                \Database::getInstance()
                    ->prepare('DELETE FROM tl_content WHERE bootstrap_parentId=? AND type=?')
                    ->execute(
                        $model->bootstrap_parentId,
                        $wrapper->getTypeName(Helper::TYPE_SEPARATOR)
                    );
            }

            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_START, static::TRIGGER_DELETE)) {
                $query = sprintf('DELETE FROM %s WHERE id=?', $model->getTable());

                \Database::getInstance()
                    ->prepare($query)
                    ->execute($model->bootstrap_parentId);
            }
        }
        // @codingStandardsIgnoreStart
        //else {
            // todo: handle seperator delete actions
        //}
        // @codingStandardsIgnoreEnd
    }

    /**
     * Make bootstrap_parentId editable in editAll and if a parent id got broken (after duplicating).
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function enableFixParentPalette()
    {
        if (\Input::get('bootstrap') === 'parent' && \Input::get('act') === 'edit') {
            $row = \ContentModel::findByPk(\Input::get('id'));

            if ($row) {
                $GLOBALS['TL_DCA']['tl_content']['palettes'][$row->type] =
                    $GLOBALS['TL_DCA']['tl_content']['palettes']['bootstrap_parent'];
            }
        } elseif (\Input::get('act') === 'editAll') {
            $wrappers = Bootstrap::getConfigVar('wrappers');

            foreach ($wrappers as $wrapper) {
                foreach ($wrapper as $name => $type) {
                    if ($name === 'start') {
                        continue;
                    }

                    MetaPalettes::appendFields('tl_content', $type['name'], 'type', array('bootstrap_parentId'));
                }
            }
        }
    }

    /**
     * Get parents for a specific item.
     *
     * @param \DataContainer $dataContainer The data container driver.
     *
     * @return array
     */
    public function getParents($dataContainer)
    {
        if ($dataContainer->id) {
            $row = \ContentModel::findByPk($dataContainer->id);

            if (!$row) {
                return array();
            }

            $helper = Helper::create($row);
            $start  = Bootstrap::getConfigVar('wrappers.' . $helper->getGroup() . '.start.name');

            if ($start) {
                $result = \ContentModel::findBy(
                    array('pid=?', 'type=?', 'sorting<?'),
                    array($row->pid, $start, $row->sorting),
                    array('order' => 'sorting DESC')
                );

                if ($result) {
                    $values = array();

                    foreach ($result as $model) {
                        $headline           = deserialize($model->headline, true);
                        $values[$model->id] = $headline['value'] ?: $model->type . ' ID: ' . $model->id;
                    }

                    return $values;
                }
            }

            return array();
        }
    }

    /**
     * Create a new wrapper element.
     *
     * @param \Model $parent  The parent element.
     * @param int    $sorting The sorting index.
     * @param string $type    The wrapper type.
     *
     * @return \ContentModel
     */
    protected function createElement($parent, &$sorting, $type = Helper::TYPE_SEPARATOR)
    {
        $model = new \ContentModel();

        if ($type == Helper::TYPE_START) {
            $sorting = ($sorting - 2);
        } else {
            $sorting                   = ($sorting + 2);
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
     * Check if action can be triggered.
     *
     * @param string $trigger Trigger name.
     * @param string $target  Target.
     * @param string $action  Trigger action.
     *
     * @return bool
     */
    protected function isTrigger($trigger, $target, $action = self::TRIGGER_CREATE)
    {
        $config = Bootstrap::getConfigVar(sprintf('wrappers.%s', $this->wrapper->getGroup()), array());

        if (array_key_exists($action, $config[$trigger]) && $config[$trigger][$action]) {
            $key = $action == static::TRIGGER_DELETE ? 'auto-delete' : 'auto-create';

            // check if count callback is defined
            if ($target == Helper::TYPE_SEPARATOR && $action == static::TRIGGER_CREATE) {
                if (!isset($config[$target]['count-existing']) || !isset($config[$target]['count-required'])) {
                    return false;
                }
            }

            return(array_key_exists($key, $config[$target]) && $config[$target][$key]);
        }

        return false;
    }

    /**
     * Save start type.
     *
     * @param mixed            $value   Value to be saved.
     * @param \Database\Result $record  Current database record.
     * @param Helper           $wrapper Wrapper helper.
     * @param array            $set     Current data set.
     * @param int              $sorting Sorting index.
     *
     * @return int
     *
     * @throws \Exception If no wrapper start element could be created.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function saveStartType($value, $record, $wrapper, $set, $sorting)
    {
        if ($record->bootstrap_parentId == '') {
            $parent = $wrapper->findPreviousElement(Helper::TYPE_START);

            if ($parent) {
                // set relation to parent element
                $set['bootstrap_parentId'] = $parent->id;

                $end = $wrapper->findRelatedElement($record, Helper::TYPE_STOP);
                $set = $this->initializeSorting($record, $set, $end, $parent);

                foreach ($set as $name => $v) {
                    $record->$name = $v;
                }

                \Database::getInstance()
                    ->prepare('UPDATE tl_content %s WHERE id=?')
                    ->set($set)
                    ->execute($record->id);
            } elseif ($this->isTrigger($wrapper->getType(), Helper::TYPE_START)) {
                // create parent if possible

                $sorting = ($sorting - 2);
                $this->createElement($record, $sorting, Helper::TYPE_START);
            } else {
                // no parent element exists, throw error

                throw new \Exception(
                    sprintf(
                        $GLOBALS['TL_LANG']['ERR']['wrapperStartNotExists'],
                        $GLOBALS['TL_LANG']['CTE'][$value][0] ?: $value
                    )
                );
            }
        }

        return $sorting;
    }

    /**
     * Create separator elements.
     *
     * @param Helper           $wrapper Wrapper helper.
     * @param \Database\Result $record  Database result.
     * @param int              $sorting Sorting index.
     *
     * @return array|int
     */
    private function createSeparators($wrapper, $record, $sorting)
    {
        $config = Bootstrap::getConfigVar(sprintf('wrappers.%s.%s', $wrapper->getGroup(), Helper::TYPE_SEPARATOR));

        $callback = $config['count-existing'];
        $instance = \Controller::importStatic($callback[0]);
        $existing = $instance->$callback[1]($record, $wrapper);

        $callback = $config['count-required'];
        $instance = \Controller::importStatic($callback[0]);
        $required = $instance->$callback[1]($record, $wrapper);

        if ($existing < $required) {
            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR)) {
                $count = ($required - $existing);

                for ($i = 0; $i < $count; $i++) {
                    $this->createElement($record, $sorting, Helper::TYPE_SEPARATOR);
                }

                $end = $wrapper->findRelatedElement(Helper::TYPE_STOP);

                if ($end && $end->sorting <= $sorting) {
                    $sorting      = ($sorting + 2);
                    $end->sorting = $sorting;
                    $end->save();

                    return array($sorting, $end);
                }
            }
        } elseif ($required < $existing) {
            if ($this->isTrigger($wrapper->getType(), Helper::TYPE_SEPARATOR, static::TRIGGER_DELETE)) {
                $count    = ($existing - $required);
                $parentId = $wrapper->isTypeOf(Helper::TYPE_START)
                    ? $record->id
                    : $record->bootstrap_parentId;

                \Database::getInstance()
                    ->prepare('DELETE FROM tl_content WHERE bootstrap_parentId=? AND type=? ORDER BY sorting DESC')
                    ->limit($count)
                    ->execute($parentId, $wrapper->getTypeName(Helper::TYPE_SEPARATOR));
            }
        }

        return $sorting;
    }

    /**
     * Initialize the sorting value if required.
     *
     * @param \Database\Result      $record The current record.
     * @param array                 $set    The current set.
     * @param \Database\Result|null $end    The end element.
     * @param \Database\Result|null $parent The parent element.
     *
     * @return array
     */
    private function initializeSorting($record, $set, $end, $parent)
    {
        // only set initial sorting
        if (!$record->sorting) {
            if ($end === null) {
                $set['sorting'] = ($parent->sorting + 2);
            } elseif ($parent !== null && $parent->sorting > $end->sorting) {
                $set['sorting'] = ($end->sorting - 2);
            }
        }

        return $set;
    }
}
