<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core;

use Netzmacht\Bootstrap\Core\Util\ArrayUtil;

/**
 * Class Config.
 *
 * @package Netzmacht\Bootstrap
 */
class Config
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
     * Get a config value.
     *
     * @param string $key     Name of the config param.
     * @param mixed  $default Default value if config is not set.
     *
     * @return mixed
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
     * Set a config value.
     *
     * @param string $key   Name of the config param.
     * @param mixed  $value The new value.
     *
     * @return $this
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
     * Remove a config param.
     *
     * @param string $key Name of the config param.
     *
     * @return $this
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
     * Consider if config param exists.
     *
     * @param string $key Name of the config param.
     *
     * @return bool
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
     * Merge config values into config.
     *
     * @param array $data New data.
     * @param null  $path Optional sub path where to merge in.
     *
     * @return $this
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
     * Import a file.
     *
     * @param string $file File path.
     *
     * @throws \RuntimeException         If file returns not an array.
     * @throws \InvalidArgumentException If file does not exists.
     *
     * @return $this
     */
    public function import($file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not found', $file));
        }

        $config = include $file;

        if (!is_array($config)) {
            throw new \RuntimeException('Loaded config is not an array');
        }

        $this->merge($config);

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
