<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Config;


class TypeFactory
{
    /**
     * @var array
     */
    private $types;

    /**
     * @param array $types
     */
    function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param $name
     * @return Type
     */
    public function create($name)
    {
        $this->guardTypeExists($name);

        if (is_callable($this->types[$name])) {
            return call_user_func($this->types[$name]);
        }

        $className = $this->types[$name];

        return new $className;
    }

    /**
     * @return Type[]
     */
    public function createAll()
    {
        $types = array();

        foreach ($this->types as $name => $type) {
            $types[$name] = $this->create($name);
        }

        return $types;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->types);
    }

    /**
     * @param $type
     */
    private function guardTypeExists($type)
    {
        if (!isset($this->types[$type])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" is not set.', $type));
        }
    }
} 