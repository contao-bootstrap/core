<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\DataContainer;

use Contao\Backend;
use Contao\BackendUser;
use Contao\Controller;
use Contao\Database;
use Contao\Database\Result;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\Message;
use Contao\Versions;
use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Config\TypeManager;
use ContaoBootstrap\Core\Config\Model\BootstrapConfigModel;
use ContaoBootstrap\Core\Event\GetMultipleConfigNamesEvent;
use Exception;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class BootstrapConfig is used for the bootstrap config.
 *
 * @package ContaoBootstrap\Core\DataContainer
 */
class BootstrapConfig
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * The type manager.
     *
     * @var TypeManager
     */
    private $typeManager;

    /**
     * Bootstrap config.
     *
     * @var Config
     */
    private $config;

    /**
     * Construct.
     *
     * @param EventDispatcher $eventDispatcher Event dispatcher.
     * @param TypeManager     $typeManager     Config type manager.
     * @param Config          $config          Bootstrap config.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(EventDispatcher $eventDispatcher, TypeManager $typeManager, Config $config)
    {
        Controller::loadLanguageFile('bootstrap_config_types');

        $this->eventDispatcher = $eventDispatcher;
        $this->typeManager     = $typeManager;
        $this->config          = $config;
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
        if (Input::get('act') == 'edit') {
            $model = BootstrapConfigModel::findByPk(\Input::get('id'));

            if ($model && $model->type && $this->typeManager->hasType($model->type)) {
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
     * @param DataContainer $dataContainer Data container driver.
     *
     * @return array
     */
    public function getTypes(DataContainer $dataContainer)
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
     * @param DataContainer $dataContainer Data container driver.
     *
     * @return array
     */
    public function getNames(DataContainer $dataContainer)
    {
        $options = array();

        if (!$dataContainer->activeRecord instanceof Result) {
            return $options;
        }

        $event = new GetMultipleConfigNamesEvent($dataContainer->activeRecord);
        $this->eventDispatcher->dispatch($event::NAME, $event);
        $options = $event->getOptions();

        if ($options) {
            return $options;
        }

        if ($dataContainer->activeRecord->override && $this->typeManager->hasType($dataContainer->activeRecord->type)) {
            $type = $this->typeManager->getType($dataContainer->activeRecord->type);

            if (!$type->isMultiple()) {
                Controller::redirect('main.php?act=error');
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
     * @param mixed         $value         The value.
     * @param DataContainer $dataContainer Data container driver.
     *
     * @return mixed
     */
    public function importFromConfig($value, DataContainer $dataContainer)
    {
        if (!$this->typeManager->hasType($value)) {
            return $value;
        }

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

                $type->extractConfig($key, $this->config, $model);
                $model->save();

                // unset parameter was only introduced in Contao 3.3
                Controller::redirect(\Backend::addToUrl('override=', true, array('override')));
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

                Message::addInfo(sprintf($GLOBALS['TL_LANG']['tl_bootstrap_config']['globalScopeWarning'], $name));
            }
        } catch (Exception $e) {
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
            Database::getInstance()
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
        if ($row['type'] && $this->typeManager->hasType($row['type'])) {
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
        return $this->config->get('config.options.dropdown.formless', array());
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
        $user = BackendUser::getInstance();

        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 0));
            Controller::redirect(\Backend::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$user->isAdmin && !$user->hasAccess('tl_bootstrap_config::published', ['alexf'])) {
            return '';
        }

        $href .= sprintf('&amp;id=%s&amp;tid=%s&amp;state=', Input::get('id'), $row['id']);

        if (!$row['published']) {
            $icon  = 'invisible.gif';
            $href .= '1';
        }

        return sprintf(
            '<a href="%s" title="%s" %s>%s</a> ',
            Backend::addToUrl($href),
            specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
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
        $user = BackendUser::getInstance();

        // Check permissions to publish
        if (!$user->isAdmin && !$user->hasAccess('tl_bootstrap_config::published', ['alexf'])) {
            Controller::log(
                'Not enough permissions to show/hide record ID "'.$configId.'"',
                'tl_bootstrap_config toggleVisibility',
                TL_ERROR
            );

            Controller::redirect('contao/main.php?act=error');
        }

        $versions = new Versions('tl_bootstrap_config', $configId);
        $versions->initialize();

        // Trigger the save_callback
        if (isset($GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'])) {
            $callbacks = (array) $GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'];

            foreach ($callbacks as $callback) {
                Controller::importStatic($callback[0]);
                $published = $this->$callback[0]->$callback[1]($published, $this);
            }
        }

        // Update the database
        Database::getInstance()
            ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
            ->set(array(
                    'tstamp'    => time(),
                    'published' => ($published ? '' : '1')
                ))
            ->execute($configId);
        $versions->create();
    }

    /**
     * Guard that icon file is valid.
     *
     * @param string $file File name.
     *
     * @throws InvalidArgumentException If icon file is not valid.
     *
     * @return string
     */
    public function guardValidIconFile($file)
    {
        if (!file_exists(TL_ROOT . '/' . $file)) {
            throw new InvalidArgumentException('File does not exists');
        }

        $categories = include TL_ROOT . '/' . $file;

        if (!is_array($categories)) {
            throw new InvalidArgumentException('File does not return a valid icon configuration');
        }

        foreach ($categories as $icons) {
            if (!is_array($icons)) {
                throw new InvalidArgumentException('File does not return a valid icon configuration');
            }
        }

        return $file;
    }
}
