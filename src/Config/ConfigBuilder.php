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


use Netzmacht\Bootstrap\Contao\Model\BootstrapConfigModel;
use Netzmacht\Bootstrap\Core\Config;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ConfigBuilder
{
    /**
     * @var array
     */
    private $types;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var int
     */
    private $themeId;

    /**
     * @param Config $config
     * @param array $types
     */
    function __construct(Config $config, array $types, $themeId)
    {
        $this->config  = $config;
        $this->types   = $types;
        $this->themeId = $themeId;
    }

    /**
     *
     */
    public function build()
    {
        $collection = BootstrapConfigModel::findPublishedByTheme($this->themeId);

        while ($collection->next()) {
            try {
                $type = $this->createType($collection->type);
                $type->buildConfig($this->config, $collection->current());
            }
            catch (\Exception $e) {
                \Controller::log(
                    'Unknown bootstrap config type "%s" (ID %s) stored in database',
                    $collection->type,
                    $collection->id
                );
            }
        }
    }

    /**
     * @param $type
     * @return ConfigType
     * @throws \RuntimeException
     */
    private function createType($type)
    {
        if (!isset($this->types[$type])) {
            throw new \RuntimeException('Config type "%s" not found');
        }

        if (is_callable($this->types[$type])) {
            return call_user_func($this->types[$type]);
        }

        $className = $this->types[$type];
        return new $className;
    }
} 