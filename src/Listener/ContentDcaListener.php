<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016-2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener;

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
