<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Util\ArrayUtil;

use function array_key_exists;
use function array_shift;

final class ArrayConfig implements Config
{
    /**
     * Construct.
     *
     * @param array<string,mixed> $config Initial config values.
     */
    public function __construct(private readonly array $config = [])
    {
    }

    /** {@inheritDoc} */
    public function get(array $key, mixed $default = null): mixed
    {
        $value = $this->config;

        while (($chunk = array_shift($key)) !== null) {
            if (! array_key_exists($chunk, $value)) {
                return $default;
            }

            $value = $value[$chunk];
        }

        return $value;
    }

    /** {@inheritDoc} */
    public function has(array $key): bool
    {
        $value = $this->config;

        while (($chunk = array_shift($key)) !== null) {
            if (! array_key_exists($chunk, $value)) {
                return false;
            }

            $value = $value[$chunk];
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $config): Config
    {
        return new self(ArrayUtil::merge($this->config, $config));
    }
}
