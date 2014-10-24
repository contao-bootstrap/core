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
     * @var Type[]
     */
    private $types;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     * @param Type[] $types
     */
    public function __construct(Config $config, $types)
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
     * @param  bool         $keysOnly
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
     * @param  bool         $keysOnly
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
     * @param  string $typeName of a multiple type
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
     * @param  bool         $keysOnly
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
     * @param $typeName
     * @return Type
     */
    public function getType($typeName)
    {
        if (!isset($this->types[$typeName])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" does not exists', $typeName));
        }

        return $this->types[$typeName];
    }

    /**
     * @param \Model\Collection $collection
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
     * @param $typeName
     * @param $type
     */
    private function guardMultipleType($typeName, Type $type)
    {
        if (!$type->isMultiple()) {
            new \RuntimeException(sprintf('Type "%s is not a multiple type.', $typeName));
        }
    }
}
