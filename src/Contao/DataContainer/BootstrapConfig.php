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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BootstrapConfig
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