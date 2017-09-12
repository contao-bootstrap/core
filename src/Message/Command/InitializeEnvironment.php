<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Environment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class InitializeEnvironmentEvent is emitted then bootstrap environment is initialized.
 *
 * @package ContaoBootstrap\Core\Event
 */
final class InitializeEnvironment extends Event
{
    const NAME = 'contao_bootstrap.core.initialize_environment';

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
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }
}
