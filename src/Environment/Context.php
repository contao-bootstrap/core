<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

interface Context
{
    /**
     * Check if context supports a given context.
     *
     * @param Context $context Another context.
     */
    public function supports(Context $context): bool;

    /**
     * Check if the context match another context.
     *
     * @param Context $context Context.
     */
    public function match(Context $context): bool;

    /**
     * Get string representation of context.
     */
    public function __toString(): string;
}
