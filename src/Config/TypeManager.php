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

/**
 * Class TypeManager is used to manage different config types.
 *
 * @package Netzmacht\Bootstrap\Core\Config
 */
class TypeManager
{
    /**
     * Config types.
     *
     * @var Type[]
     */
    private $types;

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
     * @param Type[] $types  Config types.
     */
    public function __construct(Config $config, $types)
    {
        $this->config = $config;
        $this->types  = $types;
    }

    /**
     * Get Bootstrap config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set bootstrap config.
     *
     * @param Config $config Bootstrap config.
     *
     * @return void
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get all type names.
     *
     * @return array
     */
    public function getNames()
    {
        return array_keys($this->types);
    }

    /**
     * Get unused types.
     *
     * @param bool $keysOnly Return keys only. Otherwise type objects are returned.
     *
     * @return Type[]|array
     */
    public function getUnusedTypes($keysOnly = false)
    {
        $types = array();

        foreach ($this->types as $name => $type) {
            if ($type->isMultiple()) {
                if ($type->isNameEditable()) {
                    $types[$name] = $type;
                }

                continue;
            }

            if (!$this->config->has($type->getPath())) {
                $types[$name] = $type;
            }
        }

        if ($keysOnly) {
            return array_keys($types);
        }

        return $types;
    }

    /**
     * Get existing types.
     *
     * @param bool $keysOnly Return keys only. Otherwise type objects are returned.
     *
     * @return Type[]|array
     */
    public function getExistingTypes($keysOnly = false)
    {
        $types = array_filter(
            $this->types,
            function (Type $type) {
                return $this->config->has($type->getPath());
            }
        );

        if ($keysOnly) {
            return array_keys($types);
        }

        return $types;
    }

    /**
     * Get existing names of multiple config types..
     *
     * @param string $typeName Tye name of a multiple type.
     *
     * @return array
     */
    public function getExistingNames($typeName)
    {
        $type = $this->getType($typeName);
        $this->guardMultipleType($typeName, $type);

        $config = (array) $this->config->get($type->getPath());

        return array_keys($config);
    }

    /**
     * Get types containg all global type elements as well.
     *
     * @param bool $keysOnly Return keys only. Otherwise type objects are returned.
     *
     * @return Type[]|array
     */
    public function getTypesWithGlobalScope($keysOnly = false)
    {
        $types = array_filter(
            $this->types,
            function (Type $type) {
                return $type->hasGlobalScope();
            }
        );

        if ($keysOnly) {
            return array_keys($types);
        }

        return $types;
    }

    /**
     * Get a specifiy type by name.
     *
     * @param string $typeName Type name.
     *
     * @return Type
     *
     * @throws \InvalidArgumentException If type does not exists.
     */
    public function getType($typeName)
    {
        if (!isset($this->types[$typeName])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" does not exists', $typeName));
        }

        return $this->types[$typeName];
    }

    /**
     * Build config from collection.
     *
     * @param \Model\Collection $collection Config collection.
     *
     * @return void
     */
    public function buildConfig(\Model\Collection $collection = null)
    {
        if (!$collection) {
            return;
        }

        while ($collection->next()) {
            $model = $collection->current();

            try {
                $type = $this->getType($model->type);
                $type->buildConfig($this->config, $model);
            } catch (\Exception $e) {
                \Controller::log(
                    sprintf(
                        'Unknown bootstrap config type "%s" (ID %s) stored in database',
                        $model->type,
                        $model->id
                    ),
                    __METHOD__,
                    'TL_ERROR'
                );
            }
        }
    }

    /**
     * Guard that type is a multiple type.
     *
     * @param string $typeName Type name.
     * @param Type   $type     Config type.
     *
     * @return void
     *
     * @throws \RuntimeException If type is not a multiple type.
     */
    private function guardMultipleType($typeName, Type $type)
    {
        if (!$type->isMultiple()) {
            new \RuntimeException(sprintf('Type "%s is not a multiple type.', $typeName));
        }
    }
}
