<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Util\ArrayUtil;

/**
 * Class Config.
 *
 * @package ContaoBootstrap
 */
class ArrayConfig implements Config
{
    /**
     * Config values.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Construct.
     *
     * @param array $config Initial config values.
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $chunks = $this->path($key);
        $value  = $this->config;

        while (($chunk = array_shift($chunks)) !== null) {
            if (!array_key_exists($chunk, $value)) {
                return $default;
            }

            $value = $value[$chunk];
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $chunks = $this->path($key);
        $name   = array_pop($chunks);
        $config = &$this->config;

        foreach ($chunks as $chunk) {
            if (!array_key_exists($chunk, $config)) {
                $config[$chunk] = array();
            }

            $config = &$config[$chunk];
        }

        $config[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $chunks = $this->path($key);
        $name   = array_pop($chunks);
        $config = &$this->config;

        foreach ($chunks as $chunk) {
            if (!array_key_exists($chunk, $config)) {
                return $this;
            }

            $config = &$config[$chunk];
        }

        unset($config[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $chunks = $this->path($key);
        $value  = $this->config;

        while (($chunk = array_shift($chunks)) !== null) {
            if (!array_key_exists($chunk, $value)) {
                return false;
            }

            $value = $value[$chunk];
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $data, $path = null)
    {
        if ($path) {
            $config = (array) $this->get($path);
            $config = ArrayUtil::merge($config, $data);

            $this->set($path, $config);
        } else {
            $this->config = ArrayUtil::merge($this->config, $data);
        }

        return $this;
    }

    /**
     * Convert string path to array, so that always an array is used.
     *
     * @param mixed $path Passed path value.
     *
     * @return array
     */
    private function path($path)
    {
        if (is_string($path)) {
            return explode('.', $path);
        }

        return (array) $path;
    }
}
