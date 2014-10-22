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
use Netzmacht\Bootstrap\Core\Config\ConfigTypeFactory;
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
     * @var ConfigTypeFactory
     */
    private $configTypeFactory;

    /**
     * @var TypeManager
     */
    private $typeManager;

    /**
     * Construct
     */
    function __construct()
    {
        parent::__construct();

        $this->eventDispatcher   = $GLOBALS['container']['event-dispatcher'];
        $this->configTypeFactory = $GLOBALS['container']['bootstrap.config-type-factory'];
        $this->typeManager       = $GLOBALS['container']['bootstrap.config-type-manager'];;

        $this->loadLanguageFile('bootstrap_config_types');
        $this->loadLanguageFile('default');
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
            }
        }
    }

    /**
     * @param \DataContainer $dataContainer
     * @return array
     */
    public function getTypes(\DataContainer $dataContainer)
    {
        $options = array();

        if (!$dataContainer->activeRecord) {
            $types = $this->typeManager->getNames();
        }
        elseif ($dataContainer->activeRecord->override) {
            $types = $this->typeManager->getExistingTypes();
        }
        else {
            $types = $this->typeManager->getUnusedTypes();
        }

        foreach($types as $name => $type) {
            $options[$name] = $name;
        }

        return $options;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function formatGroup($value)
    {

        if (isset($GLOBALS['TL_LANG']['bootstrap_config_types'][$value])) {
            return $GLOBALS['TL_LANG']['bootstrap_config_types'][$value];
        }

        return $value;
    }

    /**
     * @param $value
     * @param \DataContainer $dc
     */
    public function saveGlobalScope($value, \DataContainer $dc)
    {
        $type   = $this->typeManager->getType($value);
        $global = $type->hasGlobalScope();

        if ($global != $dc->activeRecord->global) {
            \Database::getInstance()
                ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
                ->set(array('global' => $global))
                ->execute($dc->id);;
        }

        if ($dc->activeRecord->override && \Input::get('override')) {
            if (!$type->isMultiple() || $dc->activeRecord->name) {
                $key = $type->getPath();

                if ($type->isMultiple()) {
                    $key .= $dc->activeRecord->name;
                }

                $model = BootstrapConfigModel::findByPk($dc->id);
                $model->mergeRow($dc->activeRecord->row());

                $type->extractConfig($key, Bootstrap::getConfig(), $model);
                $model->save();

                $this->redirect($this->addToUrl('override='));
            }
        }

        return $value;
    }

    /**
     * @param $table
     * @param $configId
     * @param $row
     * @param \DataContainer $dc
     */
    public function addOverrideInformation($table, $configId, $row, \DataContainer $dc)
    {
        if ($table == 'tl_bootstrap_config' && \Input::get('override')) {
            \Database::getInstance()
                ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
                ->set(array('override' => true))
                ->execute($configId);
        }
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
            $this->log('Not enough permissions to show/hide record ID "'.$configId.'"', 'tl_bootstrap_config toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_bootstrap_config', $configId);

        // Trigger the save_callback
        if (isset($GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'])) {
            foreach ((array)$GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'] as $callback) {
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
    public function guardFileExists($file)
    {
        if (!file_exists(TL_ROOT . '/' . $file)) {
            throw new \InvalidArgumentException('File does not exists');
        }

        $icons = include TL_ROOT . '/' . $file;

        if (!is_array($icons)) {
            throw new \InvalidArgumentException('File does not return a valid icon configuration');
        }

        return $file;
    }

} 