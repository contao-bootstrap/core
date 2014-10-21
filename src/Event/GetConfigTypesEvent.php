<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Event;


use Symfony\Component\EventDispatcher\Event;

class GetConfigTypesEvent extends Event
{
    const NAME = 'bootstrap.get-config-types';

    /**
     * @var array
     */
    private $types = array();

    /**
     * @param $name
     * @param $factory
     * @return $this
     */
    public function addType($name, $factory)
    {
        $this->types[$name] = $factory;

        return $this;
    }

    /**
     * @param array $types
     * @return $this
     */
    public function addTypes(array $types)
    {
        foreach ($types as $name => $factory) {
            $this->addType($name, $factory);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
} 