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


use Netzmacht\Bootstrap\Core\Config\ConfigTypeFactory;
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
     * Construct
     */
    function __construct()
    {
        $this->eventDispatcher   = $GLOBALS['container']['event-dispatcher'];
        $this->configTypeFactory = $GLOBALS['container']['bootstrap.config-type-factory'];
    }

    /**
     *
     */
    public function getTypes()
    {
        return $this->configTypeFactory->getNames();
    }

    /**
     * @param $value
     * @return mixed
     */
    public function formatGroup($value)
    {
        \Controller::loadLanguageFile('bootstrap_config_types');

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
        $type   = $this->configTypeFactory->create($value);
        $global = $type->hasGlobalScope();

        if ($global != $dc->activeRecord->global) {
            \Database::getInstance()
                ->prepare('UPDATE tl_bootstrap_config %s WHERE id=?')
                ->set(array('global' => $global))
                ->execute($dc->id);;
        }

        return $value;
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
        if (is_array($GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_bootstrap_config']['fields']['published']['save_callback'] as $callback) {
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