<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\ContentElement;

/**
 * Class Wrapper provides basic functions for wrapping content elements.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\ContentElement
 */
abstract class Wrapper extends \Module
{
    /**
     * The wrapper helper.
     *
     * @var Wrapper\Helper
     */
    protected $wrapper;

    /**
     * The wrapper name.
     *
     * @var string
     */
    protected static $wrapperName = 'tabs';

    /**
     * Construct.
     *
     * @param \ContentModel $objElement Content element.
     */
    public function __construct($objElement)
    {
        parent::__construct($objElement);

        $this->wrapper = Wrapper\Helper::create($objElement);
    }

    /**
     * Generate the wrapper.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function generate()
    {
        // backend mode
        if (TL_MODE == 'BE') {
            if (!$this->wrapper->isTypeOf(Wrapper\Helper::TYPE_START) && !$this->bootstrap_parentId) {
                return sprintf(
                    '<a href="%s" class="bootstrap-wrapper-incomplete">%s</a>',
                    \Backend::addToUrl('act=edit&amp;bootstrap=parent&amp;id=' . $this->id),
                    $GLOBALS['TL_LANG']['tl_content']['fixBootstrapParent']
                );
            }

            if ($this->wrapper->isTypeOf(Wrapper\Helper::TYPE_STOP)) {
                return '';
            }

            // do not display title if element is a wrapper element
            if (version_compare(VERSION, '3.1', '<')) {
                return sprintf('<strong class="title">%s</strong>', $this->type);
            }

            return '';
        }

        $this->wrapperType = $this->wrapper->getType();

        return parent::generate();
    }
}
