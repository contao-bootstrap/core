<?php

namespace Netzmacht\Bootstrap\Core\Event;

use Symfony\Component\EventDispatcher\Event;

class RewriteCssClassesEvent extends Event
{
    /**
     * @var string
     */
    private $contaoMode;

    /**
     * @var string
     */
    private $templateName;

    /**
     * @var array
     */
    private $classes = array();


    /**
     * @param $templateName
     * @param $contaoMode
     */
    function __construct($templateName, $contaoMode)
    {
        $this->templateName = $templateName;
        $this->contaoMode   = $contaoMode;
    }


    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }


    /**
     * @param $old
     * @param $new
     * @param bool $append
     * @return $this
     */
    public function addClass($old, $new, $append=false)
    {
        if(isset($this->classes[$old]) && $append) {
            $this->classes[$old] .= ' ' . $new;
        }
        else {
            $this->classes[$old] = $new;
        }

        return $this;
    }


    /**
     * @param array $classes
     * @return $this
     */
    public function addClasses(array $classes)
    {
        foreach($classes as $old => $new) {
            if(is_array($new)) {
                $this->addClass($old, $new[0], $new[1]);
            }
            else {
                $this->addClass($old, $new);
            }
        }

        return $this;
    }


    /**
     * @param $class
     * @return $this
     */
    public function removeClass($class)
    {
        $key = array_search($class, $this->classes);

        if($key !== false) {
            unset($this->classes[$key]);
            $this->classes = array_values($this->classes);
        }

        return $this;
    }


    /**
     * @param $class
     * @return bool
     */
    public function hasClass($class)
    {
        return in_array($class, $this->classes);
    }

    /**
     * @return $this
     */
    public function resetClasses()
    {
        $this->classes = array();

        return $this;
    }


    /**
     * @return string
     */
    public function getContaoMode()
    {
        return $this->contaoMode;
    }


    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

}





