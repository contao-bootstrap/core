<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace ContaoBootstrap\Core\Event;

use Contao\Database\Result;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class GetMultipleConfigNamesEvent is emitted when getting names of multiple config types.
 *
 * @package ContaoBootstrap\Core\Event
 */
class GetMultipleConfigNamesEvent extends Event
{
    const NAME = 'bootstrap.config.get-multiple-config-names';

    /**
     * Config model.
     *
     * @var Result
     */
    private $model;

    /**
     * Options.
     *
     * @var array
     */
    private $options = array();

    /**
     * Construct.
     *
     * @param Result $model Config model.
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get model.
     *
     * @return Result
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set config name options.
     *
     * @param array $options Name options.
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }
}
