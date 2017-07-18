<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\Message\Event;

use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class EnterContextEvent.
 *
 * @package ContaoBootstrap\Core\Event
 */
class ContextEntered extends Event
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
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Get context.
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
