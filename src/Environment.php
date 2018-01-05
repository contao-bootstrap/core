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

namespace ContaoBootstrap\Core;

use Contao\LayoutModel;
use ContaoBootstrap\Core\Config\ArrayConfig;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Exception\LeavingContextFailed;
use ContaoBootstrap\Core\Message\Event\ContextEntered;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as MessageBus;

/**
 * Class Environment contain all things being provided in the bootstrap environment.
 *
 * @package ContaoBootstrap\Core
 */
final class Environment
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
    public function getConfig(): Config
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
    public function enterContext(Context $context): void
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
     * @param Context|null $currentContext Optional expected current context. Won't do anything if context not match.
     *
     * @return void
     * @throws LeavingContextFailed When context stack is empty.
     */
    public function leaveContext(?Context $currentContext = null): void
    {
        if (!$this->context) {
            throw LeavingContextFailed::noContext();
        }

        // Not in expected context. Just quit.
        if ($currentContext && !$currentContext->match($this->context)) {
            return;
        }

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
    private function switchContext(Context $context, bool $keepCurrentInStack = false): void
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
    public function isEnabled(): bool
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
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Set the layout.
     *
     * @param LayoutModel $layout Page layout.
     *
     * @return $this
     */
    public function setLayout(LayoutModel $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get the page layout.
     *
     * @return LayoutModel|null
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
