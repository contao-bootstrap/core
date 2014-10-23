<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Netzmacht\Bootstrap\Core\Bootstrap;
use Netzmacht\Bootstrap\Core\Config\TypeManager;
use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BootstrapConfig extends \Backend
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TypeManager
     */
    private $typeManager;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->eventDispatcher = $GLOBALS['container']['event-dispatcher'];
        $this->typeManager     = $GLOBALS['container']['bootstrap.config-type-manager'];

        $this->loadLanguageFile('bootstrap_config_types');
    }

    /**
     *
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

                if ($model->override) {
                    $GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['name']['inputType'] = 'select';
                }
            }
        }
    }

    /**
     * @param  \DataContainer $dataContainer
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
     * @param  \DataContainer $dataContainer
     * @return array
     */
    public function getNames(\DataContainer $dataContainer)
    {
        $options = array();

        if (!$dataContainer->activeRecord) {
            return $options;
        }

        if ($dataContainer->activeRecord->override) {
            $type  = $this->typeManager->getType($dataContainer->activeRecord->type);

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
     * @param $value
     * @param \DataContainer $dataContainer
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

                $type->extractConfig($key, Bootstrap::getConfig(), $model);
                $model->save();

                // unset parameter was only introduced in Contao 3.3
                $this->redirect($this->addToUrl('override=', true, array('override')));
            }
        }

        return $value;
    }

    /**
     * @param $table
     * @param $configId
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
     * @return array
     */
    public function getDropdownTemplates()
    {
        return Bootstrap::getConfigVar('config.options.dropdown.formless', array());
    }

    /**
     * Ändert das Aussehen des Toggle-Buttons.
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
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
     * Toggle the visibility of an element
     * @param integer
     * @param boolean
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
            ->prepare("UPDATE tl_bootstrap_config %s WHERE id=?")
            ->set(array(
                    'tstamp'    => time(),
                    'published' => ($published ? '' : '1')
                ))
            ->execute($configId);
        $this->createNewVersion('tl_bootstrap_config', $configId);
    }

    /**
     * @param $file
     * @return mixed
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
}
