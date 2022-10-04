<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

interface Config
{
    /**
     * Get a config value.
     *
     * @param string|list<string> $key     Name of the config param.
     * @param mixed|null          $default Default value if config is not set.
     */
    public function get(array|string $key, mixed $default = null): mixed;

    /**
     * Consider if config param exists.
     *
     * @param string|list<string> $key Name of the config param.
     */
    public function has(array|string $key): bool;

    /**
     * Merge new configuration values and return a new instance of Config.
     *
     * @param array<string,mixed> $config Config values.
     */
    public function merge(array $config): Config;
}
