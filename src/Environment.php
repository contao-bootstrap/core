<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core;

use ContaoBootstrap\Core\Config\ArrayConfig;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Environment\ApplicationContext;
use ContaoBootstrap\Core\Exception\LeavingContextFailed;
use ContaoBootstrap\Core\Message\Event\ContextEntered;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as MessageBus;

/**
 * Class Environment contain all things being provided in the bootstrap environment.
 *
 * @package ContaoBootstrap\Core
 */
class Environment
{
    /**
     * Bootstrap enabled state.
     *
     * @var bool
     */
    protected $enabled = false;

    /**
     * Bootstrap config.
     *
     * @var Config
     */
    protected $config;

    /**
     * Layout model of current page.
     *
     * @var \LayoutModel
     */
    private $layout;

    /**
     * Current context.
     *
     * @var Context
     */
    private $context;

    /**
     * List of contexts.
     *
     * @var Context[]
     */
    private $contextStack;

    /**
     * MessageBus.
     *
     * @var MessageBus
     */
    private $messageBus;

    /**
     * Construct.
     *
     * @param MessageBus $messageBus Message bus.
     */
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
        $this->config     = new ArrayConfig();
    }

    /**
     * Get bootstrap config.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Enter a context.
     *
     * @param Context $context Context.
     *
     * @return void
     */
    public function enterContext(Context $context)
    {
        // Already in the context.
        if ($this->context && $this->context->match($context)) {
            return;
        }

        $this->switchContext($context, true);
    }

    /**
     * Leave current context and enter the context which was used before.
     *
     * @return void
     * @throws LeavingContextFailed When context stack is empty.
     */
    public function leaveContext()
    {
        // Get last know context which was used before current context.
        $context = array_pop($this->contextStack);

        if ($context === null) {
            throw LeavingContextFailed::inContext($this->context);
        }

        $this->switchContext($context);
    }

    /**
     * Switch to another context.
     *
     * @param Context $context            New context.
     * @param bool    $keepCurrentInStack If true current context is added to the stack.
     *
     * @return void
     */
    private function switchContext(Context $context, $keepCurrentInStack = false)
    {
        $command = new BuildContextConfig($this, $context, $this->config);
        $this->messageBus->dispatch($command::NAME, $command);

        if ($command->getConfig()) {
            $this->config = $command->getConfig();
        }

        if ($keepCurrentInStack && $this->context) {
            $this->contextStack[] = $this->context;
        }

        $this->context = $context;

        $event = new ContextEntered($this, $context);
        $this->messageBus->dispatch($event::NAME, $event);
    }

    /**
     * Consider if bootstrap theme is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Enable current bootstrap theme.
     *
     * @param bool $enabled Enabled state.
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Set the layout.
     *
     * @param \LayoutModel $layout Page layout.
     *
     * @return $this
     */
    public function setLayout(\LayoutModel $layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the page layout.
     *
     * @return \LayoutModel
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
