<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

use Override;

final class ApplicationContext extends AbstractContext
{
    #[Override]
    public function supports(Context $context): bool
    {
        return $context instanceof ApplicationContext;
    }

    public static function create(): self
    {
        return new self();
    }

    #[Override]
    public function toString(): string
    {
        return 'application';
    }
}
