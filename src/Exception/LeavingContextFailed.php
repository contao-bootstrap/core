<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Exception;

use ContaoBootstrap\Core\Environment\Context;
use Throwable;

use function sprintf;

final class LeavingContextFailed extends Exception
{
    /**
     * Create exception for the current context.
     *
     * @param Context        $context  Current context.
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     *
     * @return static
     */
    public static function inContext(Context $context, int $code = 0, ?Throwable $previous = null): self
    {
        return new static(
            sprintf('Leaving context "%s" failed. Context stack is empty', $context->__toString()),
            $code,
            $previous
        );
    }

    /**
     * Create exception for no given context.
     *
     * @param int            $code     Error code.
     * @param Throwable|null $previous Previous exception.
     *
     * @return static
     */
    public static function noContext(int $code = 0, ?Throwable $previous = null): self
    {
        return new static(
            'Leaving context failed. Environment has no context.',
            $code,
            $previous
        );
    }
}
