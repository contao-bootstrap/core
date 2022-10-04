<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use Contao\LayoutModel;
use ContaoBootstrap\Core\Config\ArrayConfig;
use ContaoBootstrap\Core\Environment\Context;
use ContaoBootstrap\Core\Exception\LeavingContextFailed;
use ContaoBootstrap\Core\Message\Command\BuildContextConfig;
use ContaoBootstrap\Core\Message\Event\ContextEntered;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as MessageBus;

use function array_pop;

final class Environment
{
    /**
     * Bootstrap enabled state.
     */
    protected bool $enabled = false;

    /**
     * Bootstrap config.
     */
    protected Config $config;

    /**
     * Layout model of current page.
     */
    private LayoutModel|null $layout = null;

    /**
     * Current context.
     */
    private Context|null $context = null;

    /**
     * List of contexts.
     *
     * @var Context[]
     */
    private array $contextStack = [];

    /**
     * Construct.
     *
     * @param MessageBus $messageBus Message bus.
     */
    public function __construct(private readonly MessageBus $messageBus)
    {
        $this->config = new ArrayConfig();
    }

    /**
     * Get bootstrap config.
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Enter a context.
     *
     * @param Context $context Context.
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
     * @throws LeavingContextFailed When context stack is empty.
     */
    public function leaveContext(Context|null $currentContext = null): void
    {
        if (! $this->context) {
            throw LeavingContextFailed::noContext();
        }

        // Not in expected context. Just quit.
        if ($currentContext && ! $currentContext->match($this->context)) {
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
     */
    private function switchContext(Context $context, bool $keepCurrentInStack = false): void
    {
        $command = new BuildContextConfig($this, $context, $this->config);
        $this->messageBus->dispatch($command, $command::NAME);

        if ($keepCurrentInStack && $this->context) {
            $this->contextStack[] = $this->context;
        }

        $this->config  = $command->getConfig();
        $this->context = $context;

        $event = new ContextEntered($this, $context);
        $this->messageBus->dispatch($event, $event::NAME);
    }

    /**
     * Consider if bootstrap theme is enabled.
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
     */
    public function getLayout(): LayoutModel|null
    {
        return $this->layout;
    }
}
