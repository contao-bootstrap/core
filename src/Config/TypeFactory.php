<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;

/**
 * Class TypeFactory creates config types.
 *
 * @package ContaoBootstrap\Core\Config
 */
class TypeFactory
{
    /**
     * Bootstrap config.
     *
     * @var Config
     */
    private $config;

    /**
     * Construct.
     *
     * @param Config $config Bootstrap config.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
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

        $type = $this->config->get('config.types.' . $name);

        if (is_callable($type)) {
            return call_user_func($type);
        }

        $className = $type;

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

        foreach ($this->getNames() as $name) {
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
        return array_keys($this->config->get('config.types', []));
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
        if (!$this->config->has('config.types.' . $type)) {
            throw new \InvalidArgumentException(sprintf('Type "%s" is not set.', $type));
        }
    }
}
