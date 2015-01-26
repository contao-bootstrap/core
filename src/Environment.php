<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Environment contain all things being provided in the bootstrap environment.
 *
 * @package Netzmacht\Bootstrap\Core
 */
class Environment
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

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
     * Icon set.
     *
     * @var IconSet
     */
    protected $iconSet;

    /**
     * Layout model of current page.
     *
     * @var \LayoutModel
     */
    private $layout;

    /**
     * Construct.
     *
     * @param Config                   $config          Bootstrap config.
     * @param IconSet                  $iconSet         Icon set.
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher.
     */
    public function __construct(Config $config, IconSet $iconSet, EventDispatcherInterface $eventDispatcher)
    {
        $this->config          = $config;
        $this->iconSet         = $iconSet;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Get the icon set.
     *
     * @return IconSet
     */
    public function getIconSet()
    {
        return $this->iconSet;
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
