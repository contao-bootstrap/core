<?php

/**
 * Contao Bootstrap
 *
 * @package    contao-bootstrap
 * @subpackage Core
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0 https://github.com/contao-bootstrap/core
 * @filesource
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use ContaoBootstrap\Core\Environment;

/**
 * Data container helper class for tl_content.
 *
 * @package ContaoBootstrap\Core\DataContainer
 */
final class ContentDcaListener
{
    /**
     * Bootstrap environment.
     *
     * @var Environment
     */
    private $environment;

    /**
     * Content constructor.
     *
     * @param Environment $environment Bootstrap environment.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get all data attributes.
     *
     * @return array
     */
    public function getDataAttributes(): array
    {
        return $this->environment->getConfig()->get('form.data-attributes', array());
    }
}
