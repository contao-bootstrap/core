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

namespace ContaoBootstrap\Core\Message\Command;

use ContaoBootstrap\Core\Config;
use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Environment\Context;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class BuildContextConfig.
 *
 * @package ContaoBootstrap\Core\Message\Command
 */
final class BuildContextConfig extends Event
{
    const NAME = 'contao_bootstrap.core.build_context_config';

    /**
     * Context config.
     *
     * @var Config
     */
    private $config;

    /**
     * Bootstrap config.
     *
     * @var Context
     */
    private $context;

    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * BuildContextConfig constructor.
     *
     * @param Environment $environment Environment.
     * @param Context     $context     Context.
     * @param Config      $config      Config.
     */
    public function __construct(Environment $environment, Context $context, Config $config)
    {
        $this->context     = $context;
        $this->environment = $environment;
        $this->config      = $config;
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

    /**
     * Get config.
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Set config.
     *
     * @param Config $config Config.
     *
     * @return $this
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }
}
