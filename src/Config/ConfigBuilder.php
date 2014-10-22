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


use Netzmacht\Bootstrap\Core\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConfigBuilder
{
    /**
     * @var ConfigTypeFactory
     */
    private $typeFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var \Model\Collection
     */
    private $collection;

    /**
     * @param Config $config
     * @param ConfigTypeFactory $typeFactory
     * @param \Model\Collection $collection
     */
    function __construct(Config $config, ConfigTypeFactory $typeFactory, \Model\Collection $collection)
    {
        $this->config      = $config;
        $this->typeFactory = $typeFactory;
        $this->collection  = $collection;
    }

    /**
     *
     */
    public function build()
    {
        while ($this->collection->next()) {
            $model = $this->collection->current();

            try {
                $type = $this->typeFactory->create($model->type);
                $type->buildConfig($this->config, $model);
            }
            catch (\Exception $e) {
                \Controller::log(
                    'Unknown bootstrap config type "%s" (ID %s) stored in database',
                    $model->type,
                    $model->id
                );
            }
        }
    }
} 