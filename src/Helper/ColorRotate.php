<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Helper;

use function floor;
use function sprintf;

/**
 * Inspired by https://github.com/menatwork/semantic_html5/blob/master/src/Backend/Helper.php.
 */
final class ColorRotate
{
    /**
     * Color cache.
     *
     * @var array<string,string>
     */
    private array $cache = [];

    /**
     * Rotating color value.
     */
    private float $rotatingColor;

    /**
     * Initial saturation.
     */
    private float $saturation;

    /**
     * Color Value.
     */
    private float $value;

    /**
     * @param float $rotatingColor Initial hue value.
     * @param float $saturation    Saturation.
     * @param float $value         Value.
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
     */
    public function getColor(string $identifier): string
    {
        if (! isset($this->cache[$identifier])) {
            $this->cache[$identifier] = $this->rotateColor();
        }

        return $this->cache[$identifier];
    }

    /**
     * Rotate the color value.
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
     * @see    http://stackoverflow.com/a/3597447
     *
     * @param float $hue        Hue.
     * @param float $saturation Saturation.
     * @param float $value      Color value.
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private function convertHSVtoRGB(float $hue, float $saturation, float $value): string
    {
        // First
        $hue *= 6;

        // Second
        $i = floor($hue);
        $f = $hue - $i;

        // Third
        $m = $value * (1 - $saturation);
        $n = $value * (1 - $saturation * $f);
        $k = $value * (1 - $saturation * (1 - $f));

        // Forth
        switch ($i) {
            case 0:
                [$red, $green, $blue] = [$value, $k, $m];
                break;
            case 1:
                [$red, $green, $blue] = [$n, $value, $m];
                break;
            case 2:
                [$red, $green, $blue] = [$m, $value, $k];
                break;
            case 3:
                [$red, $green, $blue] = [$m, $n, $value];
                break;
            case 4:
                [$red, $green, $blue] = [$k, $m, $value];
                break;
            case 5:
            case 6:
                // for when $H=1 is given
            default:
                [$red, $green, $blue] = [$value, $m, $n];
                break;
        }

        return sprintf('#%02x%02x%02x', $red * 255, $green * 255, $blue * 255);
    }
}
