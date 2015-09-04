<?php

/**
 * @package   contao-bootstrap
 * @author    David Molineus <david.molineus@netzmacht.de>
 * @license   LGPL 3+
 * @copyright 2013-2015 netzmacht creative David Molineus
 */

namespace Netzmacht\Bootstrap\Core\Contao\DataContainer;

use Bit3\Contao\MetaPalettes\MetaPalettes;
use Netzmacht\Bootstrap\Core\Bootstrap;
use Input;
use LayoutModel;

/**
 * Class Layout is used in tl_layout.
 *
 * @package Netzmacht\Bootstrap\Core\Contao\DataContainer
 */
class Layout
{
    /**
     * Modify palette if bootstrap is used.
     *
     * Hook palettes_hook (MetaPalettes) is called.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function generatePalette()
    {
        // @codingStandardsIgnoreStart
        // TODO: How to handle editAll actions?
        // @codingStandardsIgnoreEnd

        if (Input::get('table') != 'tl_layout' || Input::get('act') != 'edit') {
            return;
        }

        $layout = LayoutModel::findByPk(Input::get('id'));

        // dynamically render palette so that extensions can plug into default palette
        if ($layout->layoutType == 'bootstrap') {
            $metaPalettes                             = & $GLOBALS['TL_DCA']['tl_layout']['metapalettes'];
            $metaPalettes['__base__']                 = $this->getMetaPaletteOfPalette('tl_layout');
            $metaPalettes['default extends __base__'] = Bootstrap::getConfigVar('layout.metapalette', array());

            // unset default palette. otherwise metapalettes will not render this palette
            unset($GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);

            $subSelectPalettes = Bootstrap::getConfigVar('layout.metasubselectpalettes', array());

            foreach ($subSelectPalettes as $field => $meta) {
                foreach ($meta as $value => $definition) {
                    unset($GLOBALS['TL_DCA']['tl_layout']['subpalettes'][$field . '_' . $value]);
                    $GLOBALS['TL_DCA']['tl_layout']['metasubselectpalettes'][$field][$value] = $definition;
                }
            }
        } else {
            MetaPalettes::appendFields('tl_layout', 'title', array('layoutType'));
        }
    }

    /**
     * Creates an meta palette of a palettes.
     *
     * @param string $table Database table name.
     * @param string $name  Palette name.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected function getMetaPaletteOfPalette($table, $name = 'default')
    {
        $palette     = $GLOBALS['TL_DCA'][$table]['palettes'][$name];
        $metaPalette = array();
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
