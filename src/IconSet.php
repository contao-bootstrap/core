<?php

namespace Netzmacht\Bootstrap\Core;

class IconSet
{
    /**
     * @var string
     */
    protected $iconSetName;

    /**
     * @var array
     */
    protected $icons = array();

    /**
     * @var string
     */
    protected $template;

    /**
     * @param mixed $iconSetName
     * @return $this
     */
    public function setIconSetName($iconSetName)
    {
        $this->iconSetName = $iconSetName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIconSetName()
    {
        return $this->iconSetName;
    }

    /**
     * @param mixed $icons
     * @return $this
     */
    public function setIcons($icons)
    {
        $this->icons = $icons;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcons()
    {
        return $this->icons;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $icon
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
     * @param $icon
     * @param $class
     * @return string
     */
    public function generateIcon($icon, $class = null)
    {
        return sprintf($this->template, $icon . ($class == null ? '' : ' ' . $class));
    }
}
