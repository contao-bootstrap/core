<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Listener\Dca;

use Contao\CoreBundle\Framework\Adapter;
use Contao\Input;
use Contao\LayoutModel;
use ContaoBootstrap\Core\Environment;
use ContaoCommunityAlliance\MetaPalettes\MetaPalettes;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Netzmacht\Contao\Toolkit\Dca\Listener\AbstractListener;
use Override;

use function array_shift;
use function explode;
use function preg_match;

final class LayoutDcaListener extends AbstractListener
{
    /** @param Adapter<Input> $inputAdapter */
    public function __construct(
        DcaManager $dcaManager,
        private readonly Environment $environment,
        private readonly Adapter $inputAdapter,
        private readonly RepositoryManager $repositories,
    ) {
        parent::__construct($dcaManager);
    }

    #[Override]
    public static function getName(): string
    {
        return 'tl_layout';
    }

    /**
     * Modify palette if bootstrap is used.
     *
     * Hook palettes_hook (MetaPalettes) is called.
     */
    public function generatePalette(): void
    {
        // TODO: How to handle editAll actions?
        if ($this->inputAdapter->get('table') !== 'tl_layout' || $this->inputAdapter->get('act') !== 'edit') {
            return;
        }

        /** @psalm-suppress RiskyCast */
        $layout = $this->repositories->getRepository(LayoutModel::class)->find((int) $this->inputAdapter->get('id'));

        // dynamically render palette so that extensions can plug into default palette
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        if ($layout && $layout->layoutType === 'bootstrap') {
            $definition                               = $this->getDefinition();
            $metaPalettes                             = $definition->get('metapalettes', []);
            $metaPalettes['__base__']                 = $this->convertDefaultPaletteToMetaPalette();
            $metaPalettes['default extends __base__'] = $this->environment
                ->getConfig()
                ->get(['layout', 'metapalette'], []);

            $definition->set('metapalettes', $metaPalettes);

            // unset default palette. otherwise metapalettes will not render this palette
            $definition->modify(
                'palettes',
                static function (array $palettes): array {
                    unset($palettes['default']);

                    return $palettes;
                },
            );

            $subSelectPalettes = $this->environment->getConfig()->get(['layout', 'metasubselectpalettes'], []);
            $subPalettes       = $definition->get('subpalettes', []);

            foreach ($subSelectPalettes as $field => $meta) {
                foreach ($meta as $value => $subSelectPalette) {
                    unset($subPalettes[$field . '_' . $value]);
                    $definition->set(['metasubselectpalettes', $field, $value], $subSelectPalette);
                }
            }

            $definition->set('subpalettes', $subPalettes);
        } else {
            MetaPalettes::appendFields('tl_layout', 'title', ['layoutType']);
        }
    }

    /**
     * Creates an meta palette of a palettes.
     *
     * @return array<string,list<string>>
     */
    private function convertDefaultPaletteToMetaPalette(): array
    {
        $palette     = $this->getDefinition()->get(['palettes', 'default']);
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
