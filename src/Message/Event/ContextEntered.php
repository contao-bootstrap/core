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

namespace ContaoBootstrap\Core\Message\Event;

use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EnterContextEvent.
 *
 * @package ContaoBootstrap\Core\Event
 */
final class ContextEntered extends Event
{
    const NAME = 'contao_bootstrap.core.enter_context';

    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * Environment context.
     *
     * @var Context
     */
    private $context;

    /**
     * EnterContextEvent constructor.
     *
     * @param Environment $environment Bootstrap environment.
     * @param Context     $context     Environment context.
     */
    public function __construct(Environment $environment, Context $context)
    {
        $this->environment = $environment;
        $this->context     = $context;
    }

    /**
     * Get environment.
     *
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Get context.
     *
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}
