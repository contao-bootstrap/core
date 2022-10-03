<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

interface ContaoBootstrapComponent
{
    public function addBootstrapConfiguration(ArrayNodeDefinition $builder): void;
}
