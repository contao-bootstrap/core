<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
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
