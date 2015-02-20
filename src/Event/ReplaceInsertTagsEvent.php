<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ReplaceInsertTagsEvent is emitted when a bootstrap icon set is parsed.
 *
 * It supports all insert tags which uses a tag::arg1::arg1... syntax.
 *
 * @package Netzmacht\Bootstrap\Core\Event
 */
class ReplaceInsertTagsEvent extends Event
{
    const NAME = 'bootstrap.replace-insert-tags';

    /**
     * The html representation.
     *
     * @var string
     */
    protected $html;

    /**
     * The tag name.
     *
     * @var string
     */
    protected $tag;

    /**
     * Support caching.
     *
     * @var bool
     */
    protected $cached;

    /**
     * Insert tag params.
     *
     * @var array
     */
    protected $params = array();

    /**
     * The raw insert tag.
     *
     * @var string
     */
    private $raw;

    /**
     * Construct.
     *
     * @param string $tag    The tag name.
     * @param bool   $cached Insert tag caching.
     */
    public function __construct($tag, $cached = true)
    {
        $params = explode('::', $tag);

        $this->tag    = array_shift($params);
        $this->raw    = $tag;
        $this->params = $params;
        $this->cached = $cached;
    }

    /**
     * Set html result.
     *
     * @param string $html Generated html.
     *
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get generated html.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set all params.
     *
     * @param array $params Insert tag params.
     *
     * @return $this
     *
     * @deprecated Use constructor for this. Params are in fact immutable.
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get all params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a params value.
     *
     * @param int $index Index of param.
     *
     * @return string|null
     */
    public function getParam($index)
    {
        if (isset($this->params[$index])) {
            return $this->params[$index];
        }

        return null;
    }

    /**
     * Set a param.
     *
     * @param int   $index Param index.
     * @param mixed $value Param value.
     *
     * @return $this
     *
     * @deprecated Use constructor for this. Params are in fact immutable.
     */
    public function setParam($index, $value)
    {
        $this->params[$index] = $value;

        return $this;
    }

    /**
     * Get tag name.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Get raw.
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Get insert tag caching.
     *
     * @return bool
     */
    public function isCached()
    {
        return $this->cached;
    }
}
