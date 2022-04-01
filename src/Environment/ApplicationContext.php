<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

final class ApplicationContext extends AbstractContext
{
    public function supports(Context $context): bool
    {
        return $context instanceof ApplicationContext;
    }

    public static function create(): self
    {
        return new static();
    }

    public function __toString(): string
    {
        return 'application';
    }
}
