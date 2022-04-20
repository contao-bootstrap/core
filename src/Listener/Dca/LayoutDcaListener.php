<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use Contao\Input;
use Contao\LayoutModel;
use ContaoBootstrap\Core\Environment;
use ContaoCommunityAlliance\MetaPalettes\MetaPalettes;

use function array_shift;
use function explode;
use function preg_match;

final class LayoutDcaListener
{
    /**
     * Bootstrap environment..
     */
    private Environment $environment;

    /**
     * @param Environment $environment Environment.
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * Modify palette if bootstrap is used.
     *
     * Hook palettes_hook (MetaPalettes) is called.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function generatePalette(): void
    {
        // @codingStandardsIgnoreStart
        // TODO: How to handle editAll actions?
        // @codingStandardsIgnoreEnd

        if (Input::get('table') !== 'tl_layout' || Input::get('act') !== 'edit') {
            return;
        }

        $layout = LayoutModel::findByPk(Input::get('id'));

        // dynamically render palette so that extensions can plug into default palette
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        if ($layout && $layout->layoutType === 'bootstrap') {
            $metaPalettes                             = & $GLOBALS['TL_DCA']['tl_layout']['metapalettes'];
            $metaPalettes['__base__']                 = $this->getMetaPaletteOfPalette('tl_layout');
            $metaPalettes['default extends __base__'] = $this->environment
                ->getConfig()
                ->get('layout.metapalette', []);

            // unset default palette. otherwise metapalettes will not render this palette
            unset($GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);

            $subSelectPalettes = $this->environment->getConfig()->get('layout.metasubselectpalettes', []);

            foreach ($subSelectPalettes as $field => $meta) {
                foreach ($meta as $value => $definition) {
                    unset($GLOBALS['TL_DCA']['tl_layout']['subpalettes'][$field . '_' . $value]);
                    $GLOBALS['TL_DCA']['tl_layout']['metasubselectpalettes'][$field][$value] = $definition;
                }
            }
        } else {
            MetaPalettes::appendFields('tl_layout', 'title', ['layoutType']);
        }
    }

    /**
     * Creates an meta palette of a palettes.
     *
     * @param string $table Database table name.
     * @param string $name  Palette name.
     *
     * @return array<string,list<string>>
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function getMetaPaletteOfPalette(string $table, string $name = 'default'): array
    {
        $palette     = $GLOBALS['TL_DCA'][$table]['palettes'][$name];
        $metaPalette = [];
        $legends     = explode(';', $palette);

        foreach ($legends as $legend) {
            $fields = explode(',', $legend);

            preg_match('/\{(.*)_legend(:hide)?\}/', $fields[0], $matches);

            if (isset($matches[2])) {
                $fields[0] = $matches[2];
            } else {
                array_shift($fields);
            }

            $metaPalette[$matches[1]] = $fields;
        }

        return $metaPalette;
    }
}
