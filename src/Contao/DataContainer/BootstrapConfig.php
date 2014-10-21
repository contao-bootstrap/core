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


use Netzmacht\Bootstrap\Core\Event\GetConfigTypesEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BootstrapConfig
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Construct
     */
    function __construct()
    {
        $this->eventDispatcher = $GLOBALS['container']['event-dispatcher'];
    }

    /**
     *
     */
    public function getTypes()
    {
        $event = new GetConfigTypesEvent();
        $this->eventDispatcher->dispatch($event::NAME, $event);

        $types = $event->getTypes();

        return array_keys($types);
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