<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Util;

/**
 * Class AssetsManager provides a simple interface for adding assets to the Contao super globals.
 *
 * @package Netzmacht\Bootstrap\Core\Util
 */
class AssetsManager
{
    /**
     * Add stylesheets to the global css array.
     *
     * @param array|string $stylesheets Stylesheet paths.
     * @param string|null  $prefix      Optional add a prefix to the names.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function addStylesheets($stylesheets, $prefix = null)
    {
        foreach ((array) $stylesheets as $name => $file) {
            if ($prefix) {
                $name = $prefix . '-' . $name;
            }

            if (is_numeric($name)) {
                $GLOBALS['TL_CSS'][] = $file;
            } else {
                $GLOBALS['TL_CSS'][$name] = $file;
            }
        }
    }

    /**
     * Add javascript to the global javascript array.
     *
     * @param array|string $javascripts Paths of javascript files.
     * @param string|null  $prefix      Optional name prefix.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function addJavascripts($javascripts, $prefix = null)
    {
        foreach ((array) $javascripts as $name => $file) {
            if ($prefix) {
                $name = $prefix . '-' . $name;
            }

            if (is_numeric($name)) {
                $GLOBALS['TL_JAVASCRIPT'][] = $file;
            } else {
                $GLOBALS['TL_JAVASCRIPT'][$name] = $file;
            }
        }
    }
}
