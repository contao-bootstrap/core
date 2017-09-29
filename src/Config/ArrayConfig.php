<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
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
