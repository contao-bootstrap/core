<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

interface Config
{
    /**
     * Get a config value.
     *
     * @param list<string> $key     Name of the config param.
     * @param mixed|null   $default Default value if config is not set.
     */
    public function get(array $key, mixed $default = null): mixed;

    /**
     * Consider if config param exists.
     *
     * @param list<string> $key Name of the config param.
     */
    public function has(array $key): bool;

    /**
     * Merge new configuration values and return a new instance of Config.
     *
     * @param array<string,mixed> $config Config values.
     */
    public function merge(array $config): Config;
}
