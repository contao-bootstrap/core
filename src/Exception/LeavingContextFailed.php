<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Exception;

use ContaoBootstrap\Core\Environment\Context;
use Throwable;

/**
 * Class LeavingContextFailed.
 *
 * @package ContaoBootstrap\Core\Exception
 */
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
    public static function inContext(Context $context, $code = 0, Throwable $previous = null): self
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
    public static function noContext($code = 0, Throwable $previous = null): self
    {
        return new static(
            'Leaving context failed. Environment has no context.',
            $code,
            $previous
        );
    }
}
