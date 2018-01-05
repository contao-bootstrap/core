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

namespace ContaoBootstrap\Core\Helper;

/**
 * Class ColorRotate.
 *
 * Inspired by https://github.com/menatwork/semantic_html5/blob/master/src/Backend/Helper.php.
 *
 * @package ContaoBootstrap\Grid\Helper
 */
final class ColorRotate
{
    /**
     * Color cache.
     *
     * @var array
     */
    private $cache = [];

    /**
     * Rotating color value.
     *
     * @var string
     */
    private $rotatingColor;

    /**
     * Initial saturation.
     *
     * @var float
     */
    private $saturation;

    /**
     * Color Value.
     *
     * @var float
     */
    private $value;

    /**
     * ColorRotate constructor.
     *
     * @param float|string $rotatingColor Initial hue value.
     * @param float        $saturation    Saturation.
     * @param float        $value         Value.
     */
    public function __construct(float $rotatingColor = .5, float $saturation = 0.7, float $value = .7)
    {
        $this->rotatingColor = $rotatingColor;
        $this->saturation    = $saturation;
        $this->value         = $value;
    }


    /**
     * Get the color for an identifier.
     *
     * @param string $identifier Identifier.
     *
     * @return string
     */
    public function getColor(string $identifier): string
    {
        if (!isset($this->cache[$identifier])) {
            $this->cache[$identifier] = $this->rotateColor();
        }
        
        return $this->cache[$identifier];
    }

    /**
     * Rotate the color value.
     *
     * @return string
     */
    private function rotateColor(): string
    {
        $color                = $this->convertHSVtoRGB($this->rotatingColor, $this->saturation, $this->value);
        $this->rotatingColor += .3;

        if ($this->rotatingColor > 1) {
            $this->rotatingColor -= 1;
        }

        return $color;
    }

    /**
     * Convert hsv value to rgb value.
     *
     * @param float $hue        Hue.
     * @param float $saturation Saturation.
     * @param float $value      Color value.
     *
     * @return string
     * @see    http://stackoverflow.com/a/3597447
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private function convertHSVtoRGB(float $hue, float $saturation, float $value): string
    {
        // First
        $hue *= 6;

        // Second
        $i = floor($hue);
        $f = ($hue - $i);

        // Third
        $m = ($value * (1 - $saturation));
        $n = ($value * (1 - $saturation * $f));
        $k = ($value * (1 - $saturation * (1 - $f)));

        // Forth
        switch ($i) {
            case 0:
                list($red, $green, $blue) = array($value, $k, $m);
                break;
            case 1:
                list($red, $green, $blue) = array($n, $value, $m);
                break;
            case 2:
                list($red, $green, $blue) = array($m, $value, $k);
                break;
            case 3:
                list($red, $green, $blue) = array($m, $n, $value);
                break;
            case 4:
                list($red, $green, $blue) = array($k, $m, $value);
                break;
            case 5:
            case 6:
                // for when $H=1 is given
            default:
                list($red, $green, $blue) = array($value, $m, $n);
                break;
        }

        return sprintf('#%02x%02x%02x', ($red * 255), ($green * 255), ($blue * 255));
    }
}
