<?php

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Netzmacht\Bootstrap\Core\Bootstrap;

class Settings
{

    /**
     * @return array
     */
    public function getIconSets()
    {
        $sets = Bootstrap::getConfigVar('icons.sets', array());
        $sets = array_keys($sets);
        sort($sets);

        return $sets;
    }
}
