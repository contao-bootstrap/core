<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Netzmacht\Bootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

final class InitializeEnvironmentEvent extends Event
{
    const NAME = 'bootstrap.initialize-environment';

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment  = $environment;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}
