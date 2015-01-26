<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Config;

/**
 * Class TypeFactory creates config types.
 *
 * @package Netzmacht\Bootstrap\Core\Config
 */
class TypeFactory
{
    /**
     * Type definitions.
     *
     * @var array
     */
    private $types;

    /**
     * Construct.
     *
     * @param array $types Type definitions.
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * Create a type.
     *
     * @param string $name Type name.
     *
     * @return Type
     *
     * @throws \InvalidArgumentException If type name does not exists.
     */
    public function create($name)
    {
        $this->guardTypeExists($name);

        if (is_callable($this->types[$name])) {
            return call_user_func($this->types[$name]);
        }

        $className = $this->types[$name];

        return new $className();
    }

    /**
     * Create all types.
     *
     * @return Type[]
     */
    public function createAll()
    {
        $types = array();

        foreach (array_keys($this->types) as $name) {
            $types[$name] = $this->create($name);
        }

        return $types;
    }

    /**
     * Get all names.
     *
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->types);
    }

    /**
     * Guard that type exists.
     *
     * @param string $type Type name.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If type name does not exists.
     */
    private function guardTypeExists($type)
    {
        if (!isset($this->types[$type])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" is not set.', $type));
        }
    }
}
