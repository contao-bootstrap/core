<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Bootstrap\Core\Event;

use Symfony\Component\EventDispatcher\Event;

class GetMultipleConfigNamesEvent extends Event
{
    const NAME = 'bootstrap.config.get-multiple-config-names';

    /**
     * @var \Database\Result
     */
    private $model;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return \Database\Result
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
