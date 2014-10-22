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

use Netzmacht\Bootstrap\Core\Config;

class TypeManager
{
    /**
     * @var ConfigType[]
     */
    private $types;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     * @param ConfigType[] $types
     */
    function __construct(Config $config, $types)
    {
        $this->config = $config;
        $this->types  = $types;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->types);
    }

    /**
     * @return array
     */
    public function getUnusedTypes()
    {
        $types = array();

        foreach ($this->types as $name => $type) {
            if ($type->isMultiple()) {
                $types[$name] = $type;
                continue;
            }

            if (!$this->config->has($type->getPath())) {
                $types[$name] = $type;
            }
        }

        return $types;
    }

    /**
     * @return array
     */
    public function getExistingTypes()
    {
        $types = array();

        foreach ($this->types as $name => $type) {
            if ($this->config->has($type->getPath())) {
                $types[$name] = $type;
            }
        }

        return $types;
    }

    /**
     * @param string $typeName of a multiple type
     * @return ConfigType[]
     */
    public function getExistingNames($typeName)
    {
        $type = $this->getType($typeName);
        $this->guardMultipleType($typeName, $type);

        $config = (array) $this->config->get($type->getPath());

        return array_keys($config);
    }

    /**
     * @return ConfigType[]
     */
    public function getTypesWithGlobalScope()
    {
        return array_map(
            function(ConfigType $type) {
                return $type->hasGlobalScope();
            },
            $this->types
        );
    }

    /**
     * @param $typeName
     * @return ConfigType
     */
    public function getType($typeName)
    {
        if (!isset($this->types[$typeName])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" does not exists', $typeName));
        }

        return $this->types[$typeName];
    }

    /**
     * @param $typeName
     * @param $type
     */
    public function guardMultipleType($typeName, ConfigType $type)
    {
        if (!$type->isMultiple()) {
            new \RuntimeException(sprintf('Type "%s is not a multiple type.', $typeName));
        }
    }
}