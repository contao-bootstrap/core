<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\Model;

use Contao\Model;

/**
 * Interface Specification.
 *
 * @package ContaoBootstrap\Core\Config\Model
 */
interface Specification
{
    /**
     * Consider if the specification is satisfied by the given model.
     *
     * @param Model $model Given model.
     *
     * @return bool
     */
    public function isSatisfiedBy(Model $model);

    /**
     * Transform the specification into an model query.
     *
     * @param array $columns Columns array.
     * @param array $values  Values array.
     *
     * @return void
     */
    public function buildQuery(array &$columns, array &$values);
}
