<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Config;

use Netzmacht\Bootstrap\Core\Config;
use Netzmacht\Bootstrap\Core\Util\ArrayUtil;

/**
 * ContextualConfig allows to use configurations from two different configs.
 *
 * It looks for the config in the local config first and falls back to the global one. It allows to use local config
 * without touching the global one.
 *
 * Write access is only delegated to the local config.
 *
 * @package Netzmacht\Bootstrap\Core\Config
 */
class ContextualConfig
{
    /**
     * The local config.
     *
     * @var Config
     */
    private $local;

    /**
     * The global config.
     *
     * @var Config
     */
    private $global;

    /**
     * ContextualConfig constructor.
     *
     * @param Config      $global The global config.
     * @param Config|null $local  The local config. If empty a defualt one is created.
     */
    public function __construct(Config $global, Config $local = null)
    {
        $this->global = $global;
        $this->local  = $local ?: new Config();
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if ($this->local->has($key)) {
            $value = $this->local->get($key, $default);

            if (is_array($value) && $this->global->has($key)) {
                $value = ArrayUtil::merge((array) $this->global->get($key), $value);
            }

            return $value;
        }

        return $this->global->get($key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        return $this->local->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        return $this->local->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->local->has($key) || $this->global->has($key);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $data, $path = null)
    {
        return $this->local->merge($data, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function import($file)
    {
        return $this->local->import($file);
    }
}
