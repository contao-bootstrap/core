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

class Contao
{
    /**
     * Initialize object stack
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
