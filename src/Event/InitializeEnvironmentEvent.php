<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InitializeEnvironmentEvent is emitted then bootstrap environment is initialized.
 *
 * @package Netzmacht\Bootstrap\Core\Event
 */
final class InitializeEnvironmentEvent extends Event
{
    const NAME = 'bootstrap.initialize-environment';

    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Construct.
     *
     * @param Environment $environment Bootstrap environment.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get the Bootstrap environment.
     *
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
