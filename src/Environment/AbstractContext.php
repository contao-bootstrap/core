<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

abstract class AbstractContext implements Context
{
    public function match(Context $context): bool
    {
        return $this->__toString() === $context->__toString();
    }
}
