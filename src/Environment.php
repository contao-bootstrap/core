<?php

namespace Netzmacht\Bootstrap\Core;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Environment
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var bool
     */
    protected $enabled = false;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var IconSet
     */
    protected $iconSet;

    /**
     * @var \LayoutModel
     */
    private $layout;

    /**
     * @param Config $config
     * @param IconSet $iconSet
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(Config $config, IconSet $iconSet, EventDispatcherInterface $eventDispatcher)
    {
        $this->config          = $config;
        $this->iconSet         = $iconSet;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return IconSet
     */
    public function getIconSet()
    {
        return $this->iconSet;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @param \LayoutModel $layout
     */
    public function setLayout(\LayoutModel $layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return \LayoutModel
     */
    public function getLayout()
    {
        return $this->layout;
    }
}
