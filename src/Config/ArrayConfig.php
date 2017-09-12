<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Util\ArrayUtil;

/**
 * Class Config.
 *
 * @package ContaoBootstrap
 */
final class ArrayConfig implements Config
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
    public function merge(array $data): Config
    {
        return new static(ArrayUtil::merge($this->config, $data));
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
