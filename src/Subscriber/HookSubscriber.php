<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Subscriber;

use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Hooks contains hooks being called from Contao.
 *
 * @package ContaoBootstrap\Core\Contao
 */
class HookSubscriber
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * The bootstrap environment.
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Construct.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param Environment              $environment     The bootstrap environment.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, Environment $environment)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment     = $environment;
    }

    /**
     * Initialize bootstrap at initialize system hook.
     *
     * @return void
     */
    public function initializeSystem()
    {
        $this->initializeEnvironment();
    }

    /**
     * Initialize bootstrap environment.
     *
     * @return void
     */
    protected function initializeEnvironment()
    {
        $event = new InitializeEnvironment($this->environment);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }

    /**
     * Initialize Layout.
     *
     * @param \PageModel   $page   Current page.
     * @param \LayoutModel $layout Page layout.
     *
     * @return void
     */
    public function initializeLayout(\PageModel $page, \LayoutModel $layout)
    {
        $environment = $this->environment;
        $environment->setLayout($layout);
        $environment->setEnabled($layout->layoutType == 'bootstrap');

        $event = new InitializeLayout($environment, $layout, $page);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}
