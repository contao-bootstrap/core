<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Config;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Util\ArrayUtil;

use function array_key_exists;
use function array_shift;
use function explode;
use function is_string;

final class ArrayConfig implements Config
{
    /**
     * Config values.
     *
     * @var array<string,mixed>
     */
    protected array $config = [];

    /**
     * Construct.
     *
     * @param array<string,mixed> $config Initial config values.
     */
    public function __construct(array $config = [])
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
            if (! array_key_exists($chunk, $value)) {
                return $default;
            }

            $value = $value[$chunk];
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function has($key): bool
    {
        $chunks = $this->path($key);
        $value  = $this->config;

        while (($chunk = array_shift($chunks)) !== null) {
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

    /**
     * Convert string path to array, so that always an array is used.
     *
     * @param string|list<string> $path Passed path value.
     *
     * @return list<string>
     */
    private function path($path): array
    {
        if (is_string($path)) {
            return explode('.', $path);
        }

        return $path;
    }
}
