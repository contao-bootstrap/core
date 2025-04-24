<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

use Override;

abstract class AbstractContext implements Context
{
    #[Override]
    public function match(Context $context): bool
    {
        return $this->toString() === $context->toString();
    }
}
