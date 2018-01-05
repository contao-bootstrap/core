<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2017 netzmacht creative David Molineus
 */

namespace spec\ContaoBootstrap\Core\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use ContaoBootstrap\Core\ContaoManager\Plugin;
use PhpSpec\ObjectBehavior;

class PluginSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Plugin::class);
    }

    function it_is_a_bundle_plugin()
    {
        $this->shouldImplement(BundlePluginInterface::class);
    }

    function it_loads_after_contao_core(ParserInterface $parser)
    {
        $this->getBundles($parser)[0]->getLoadAfter()->shouldContain(ContaoCoreBundle::class);
    }
}
