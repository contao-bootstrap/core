<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use ContaoBootstrap\Core\Environment;

final class ContentDcaListener
{
    /**
     * Bootstrap environment.
     */
    private Environment $environment;

    /**
     * @param Environment $environment Bootstrap environment.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Get all data attributes.
     *
     * @deprecated
     *
     * @return array<int,string>
     */
    public function getDataAttributes(): array
    {
        return $this->environment->getConfig()->get('form.data-attributes', []);
    }
}
