<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Config\TypeManager;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Event\GetMultipleConfigNamesEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BootstrapConfig is used for the bootstrap config.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\DataContainer
 */
class BootstrapConfig extends \Backend
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * The type manager.
     *
     * @var TypeManager
     */
    private $typeManager;

    /**
     * Construct.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct()
    {
        parent::__construct();

        $this->eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $this->typeManager     = $GLOBALS['container']['bootstrap.config-type-manager'];

        $this->loadLanguageFile('bootstrap_config_types');
    }

    /**
     * Add the name field to the palette depending on the config type.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function addNameToPalette()
    {
        if (\Input::get('act') == 'edit') {
            $model = BootstrapConfigModel::findByPk(\Input::get('id'));

            if ($model && $model->type) {
                $type = $this->typeManager->getType($model->type);

                if ($type->isMultiple()) {
                    $GLOBALS['TL_DCA']['tl_bootstrap_config']['metapalettes']['default']['type'][] = 'name';
                }

                if ($model->override || !$type->isNameEditable()) {
                    $GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['name']['inputType'] = 'select';
                }
            }
        }
    }

    /**
     * Get all types.
     *
     * @param \DataContainer $dataContainer Data container driver.
     *
     * @return array
     */
    public function getTypes(\DataContainer $dataContainer)
    {
        $options = array();

        if (!$dataContainer->activeRecord) {
            $types = $this->typeManager->getNames();
        } elseif ($dataContainer->activeRecord->override) {
            $types = $this->typeManager->getExistingTypes();
        } else {
            $types = $this->typeManager->getUnusedTypes();
        }

        foreach (array_keys($types) as $name) {
            $options[$name] = $name;
        }

        return $options;
    }

    /**
     * Get config names.
     *
     * @param \DataContainer $dataContainer Data container driver.
     *
     * @return array
     */
    public function getNames(\DataContainer $dataContainer)
    {
        $options = array();

        if (!$dataContainer->activeRecord) {
            return $options;
        }

        $event = new GetMultipleConfigNamesEvent($dataContainer->activeRecord);
        $this->getEventDispatcher()->dispatch($event::NAME, $event);
        $options = $event->getOptions();

        if ($options) {
            return $options;
        }

        if ($dataContainer->activeRecord->override) {
            $type = $this->typeManager->getType($dataContainer->activeRecord->type);

            if (!$type->isMultiple()) {
                $this->redirect('main.php?act=error');
            }

            $names = $this->typeManager->getExistingNames($dataContainer->activeRecord->type);

            foreach ($names as $type) {
                $options[$type] = $type;
            }
        }

        return $options;
    }

    /**
     * Import from config.
     *
     * @param mixed          $value         The value.
     * @param \DataContainer $dataContainer Data container driver.
     *
     * @return mixed
     */
    public function importFromConfig($value, \DataContainer $dataContainer)
    {
        $type = $this->typeManager->getType($value);

        if ($dataContainer->activeRecord->override && \Input::get('override')) {
            if (!$dataContainer->activeRecord->name) {
                $dataContainer->activeRecord->name = \Input::post('name');
            }

            if (!$type->isMultiple() || $dataContainer->activeRecord->name) {
                $key = $type->getPath();

                if ($type->isMultiple()) {
                    $key .= '.' . $dataContainer->activeRecord->name;
                }

                $model = BootstrapConfigModel::findByPk($dataContainer->id);

                $model->type = $value;
                $model->name = $dataContainer->activeRecord->name;

                $type->extractConfig($key, Bootstrap::getConfig(), $model);
                $model->save();

                // unset parameter was only introduced in Contao 3.3
                $this->redirect($this->addToUrl('override=', true, array('override')));
            }
        }

        return $value;
    }

    /**
     * Add warning if config type has a global scope.
     *
     * This method is triggered by the load_callback.
     *
     * @param string $value The config type.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function warnByGlobalConfig($value)
    {
        if (!$value) {
            return $value;
        }

        try {
            $type = $this->typeManager->getType($value);

            if ($type->hasGlobalScope()) {
                $name = empty($GLOBALS['TL_LANG']['bootstrap_config_type'][$value])
                    ? $value
                    : $GLOBALS['TL_LANG']['bootstrap_config_type'][$value];

                \Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_bootstrap_config']['globalScopeWarning'], $name));
            }
        } catch (\Exception $e) {
            // Do not throw, it's just a usability notice.
        }

        return $value;
    }

    /**
     * Enable override option.
     *
     * @param string $table    Table name.
     * @param int    $configId The config id.
     *
     * @return void
     */
    public function addOverrideInformation($table, $configId)
    {
        if ($table == 'tl_bootstrap_config' && \Input::get('override')) {
            \Database::getInstance()
                ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
                ->set(array('override' => true))
                ->execute($configId);
        }
    }

    /**
     * Generate the label.
     *
     * @param array  $row   Current row.
     * @param string $label The auto created label.
     *
     * @return string
     */
    public function generateLabel(array $row, $label)
    {
        if ($row['type']) {
            $type = $this->typeManager->getType($row['type']);

            if ($type->isMultiple()) {
                $label .= ': ' . $row['name'];
            }
        }

        $label .= '<p class="tl_gray"> ' . ($row['description']) . '</p>';

        return $label;
    }

    /**
     * Get dropdown templates.
     *
     * @return array
     */
    public function getDropdownTemplates()
    {
        return Bootstrap::getConfigVar('config.options.dropdown.formless', array());
    }

    /**
     * Change the toggle icon.
     *
     * @param array  $row        Current row.
     * @param string $href       Pre generated href.
     * @param string $label      Pre generated label.
     * @param string $title      Pre generated title.
     * @param string $icon       Pre generated icon.
     * @param string $attributes Pre generated attributes.
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        $user = \BackendUser::getInstance();

        if (strlen(\Input::get('tid'))) {
            $this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 0));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->isAdmin && !$user->hasAccess('tl_bootstrap_config::published', 'alexf')) {
            return '';
        }

        $href .= sprintf('&amp;id=%s&amp;tid=%s&amp;state=', \Input::get('id'), $row['id']);

        if (!$row['published']) {
            $icon  = 'invisible.gif';
            $href .= '1';
        }

        return sprintf(
            '<a href="%s" title="%s" %s>%s</a> ',
            $this->addToUrl($href),
            specialchars($title),
            $attributes,
            $this->generateImage($icon, $label)
        );
    }

    /**
     * Toggle the visibility of an element.
     *
     * @param int  $configId  The config id.
     * @param bool $published The published state.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function toggleVisibility($configId, $published)
    {
        $user = \BackendUser::getInstance();

        // Check permissions to publish
        if (!$user->isAdmin && !$user->hasAccess('tl_bootstrap_config::published', 'alexf')) {
            $this->log(
                'Not enough permissions to show/hide record ID "'.$configId.'"',
                'tl_bootstrap_config toggleVisibility',
                TL_ERROR
            );

            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_bootstrap_config', $configId);

        // Trigger the save_callback
        if (isset($GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'])) {
            $callbacks = (array) $GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'];

            foreach ($callbacks as $callback) {
                $this->import($callback[0]);
                $published = $this->$callback[0]->$callback[1]($published, $this);
            }
        }

        // Update the database
        \Database::getInstance()
            ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
            ->set(array(
                    'tstamp'    => time(),
                    'published' => ($published ? '' : '1')
                ))
            ->execute($configId);
        $this->createNewVersion('tl_bootstrap_config', $configId);
    }

    /**
     * Guard that icon file is valid.
     *
     * @param string $file File name.
     *
     * @throws \InvalidArgumentException If icon file is not valid.
     *
     * @return string
     */
    public function guardValidIconFile($file)
    {
        if (!file_exists(TL_ROOT . '/' . $file)) {
            throw new \InvalidArgumentException('File does not exists');
        }

        $categories = include TL_ROOT . '/' . $file;

        if (!is_array($categories)) {
            throw new \InvalidArgumentException('File does not return a valid icon configuration');
        }

        foreach ($categories as $icons) {
            if (!is_array($icons)) {
                throw new \InvalidArgumentException('File does not return a valid icon configuration');
            }
        }

        return $file;
    }

    /**
     * Get the event dispatcher.
     *
     * @return EventDispatcherInterface
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function getEventDispatcher()
    {
        return $GLOBALS['container']['event-dispatcher'];
    }
}
