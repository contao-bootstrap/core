<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core;

/**
 * Class IconSet handles an icon set in the contao context.
 *
 * @package Netzmacht\Bootstrap\Core
 */
class IconSet
{
    /**
     * The icon set name.
     *
     * @var string
     */
    protected $iconSetName;

    /**
     * The icons in the set.
     *
     * @var array
     */
    protected $icons = array();

    /**
     * The icon template.
     *
     * @var string
     */
    protected $template;

    /**
     * Set icon set name.
     *
     * @param string $iconSetName New icon set name.
     *
     * @return $this
     */
    public function setIconSetName($iconSetName)
    {
        $this->iconSetName = $iconSetName;

        return $this;
    }

    /**
     * Get icon set name.
     *
     * @return string
     */
    public function getIconSetName()
    {
        return $this->iconSetName;
    }

    /**
     * Set the icons.
     *
     * @param array $icons The icons. being grouped under a category.
     *
     * @return $this
     */
    public function setIcons($icons)
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * Get all icons.
     *
     * @return array
     */
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     * Set icon template.
     *
     * @param string $template The icon template. Use a %s as icon placeholder.
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Consider if icon exists.
     *
     * @param string $icon Icon name.
     *
     * @return bool
     */
    public function hasIcon($icon)
    {
        foreach ($this->icons as $icons) {
            if (in_array($icon, $icons)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate an icon html representation.
     *
     * @param string $icon  Icon name.
     * @param string $class Css class name.
     *
     * @return string
     */
    public function generateIcon($icon, $class = null)
    {
        return sprintf($this->template, $icon . ($class == null ? '' : ' ' . $class));
    }
}
