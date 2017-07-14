<?php

/**
 * @package    Website
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\Helper;

/**
 * Class ColorRotate.
 *
 * Inspired by https://github.com/menatwork/semantic_html5/blob/master/src/Backend/Helper.php.
 *
 * @package ContaoBootstrap\Grid\Helper
 */
class ColorRotate
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
    public function __construct($rotatingColor = .5, $saturation = 0.7, $value = .7)
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
     * @return mixed
     */
    public function getColor($identifier)
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
    private function rotateColor()
    {
        $color = $this->convertHSVtoRGB($this->rotatingColor, $this->saturation, $this->value);
        $this->rotatingColor += .3;

        if ($this->rotatingColor > 1) {
            $this->rotatingColor -= 1;
        }

        return $color;
    }

    /**
     * Convert hsv value to rgb value.
     *
     * @see http://stackoverflow.com/a/3597447
     *
     * @param integer $saturation Saturation.
     * @param double  $value      Color value.
     *
     * @return string
     */
    private function convertHSVtoRGB($hue, $saturation, $value)
    {
        //1
        $hue *= 6;
        //2
        $I = floor($hue);
        $F = $hue - $I;
        //3
        $M = $value * (1 - $saturation);
        $N = $value * (1 - $saturation * $F);
        $K = $value * (1 - $saturation * (1 - $F));
        //4
        switch ($I) {
            case 0:
                list($red, $green, $blue) = array($value, $K, $M);
                break;
            case 1:
                list($red, $green, $blue) = array($N, $value, $M);
                break;
            case 2:
                list($red, $green, $blue) = array($M, $value, $K);
                break;
            case 3:
                list($red, $green, $blue) = array($M, $N, $value);
                break;
            case 4:
                list($red, $green, $blue) = array($K, $M, $value);
                break;
            case 5:
            case 6: //for when $H=1 is given
            default:
                list($red, $green, $blue) = array($value, $M, $N);
                break;
        }

        return sprintf('#%02x%02x%02x', $red * 255, $green * 255, $blue * 255);
    }
}
