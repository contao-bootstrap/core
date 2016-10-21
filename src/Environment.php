<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core;

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
     * Construct.
     *
     * @param Config  $config  Bootstrap config.
     */
    public function __construct(Config $config)
    {
        $this->config  = $config;
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
