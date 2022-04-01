<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

interface Config
{
    /**
     * Get a config value.
     *
     * @param string|list<string> $key     Name of the config param.
     * @param mixed               $default Default value if config is not set.
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Consider if config param exists.
     *
     * @param string|list<string> $key Name of the config param.
     */
    public function has($key): bool;

    /**
     * Merge new configuration values and return a new instance of Config.
     *
     * @param array<string,mixed> $config Config values.
     *
     * @return static
     */
    public function merge(array $config): Config;
}
