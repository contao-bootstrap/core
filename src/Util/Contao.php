<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Util;

/**
 * Class Contao just provide a static method to initialize the Contao stack.
 *
 * @package Netzmacht\Bootstrap\Core\Util
 */
class Contao
{
    /**
     * Initialize object stack.
     *
     * @return void
     */
    public static function intializeObjectStack()
    {
        \Config::getInstance();
        \Environment::getInstance();
        \Input::getInstance();

        static::getUser();

        \Database::getInstance();
    }

    /**
     * Get the current Contao user.
     *
     * @return \BackendUser|\FrontendUser|null
     */
    public static function getUser()
    {
        if (TL_MODE == 'BE') {
            return \BackendUser::getInstance();
        } elseif (TL_MODE == 'FE') {
            return \FrontendUser::getInstance();
        }

        return null;
    }
}
