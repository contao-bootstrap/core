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

namespace ContaoBootstrap\Core\Listener;

use ContaoBootstrap\Core\Environment;
use ContaoBootstrap\Core\Message\Command\InitializeEnvironment;
use ContaoBootstrap\Core\Message\Command\InitializeLayout;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Hooks contains hooks being called from Contao.
 *
 * @package ContaoBootstrap\Core\Contao
 */
final class HookListener
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
    public function initializeSystem(): void
    {
        $this->initializeEnvironment();
    }

    /**
     * Initialize bootstrap environment.
     *
     * @return void
     */
    protected function initializeEnvironment(): void
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
    public function initializeLayout(\PageModel $page, \LayoutModel $layout): void
    {
        $environment = $this->environment;
        $environment->setLayout($layout);
        $environment->setEnabled($layout->layoutType == 'bootstrap');

        $event = new InitializeLayout($environment, $layout, $page);
        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}
