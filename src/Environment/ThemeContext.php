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

namespace ContaoBootstrap\Core\Environment;

/**
 * Class ThemeContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
final class ThemeContext extends AbstractContext
{
    /**
     * Theme id.
     *
     * @var int
     */
    private $themeId;

    /**
     * ThemeContext constructor.
     *
     * @param int $themeId Theme id.
     */
    private function __construct(int $themeId)
    {
        $this->themeId = $themeId;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Context $context): bool
    {
        if ($context instanceof ApplicationContext) {
            return true;
        }

        if ($context instanceof ThemeContext) {
            return $this->match($context);
        }

        return false;
    }

    /**
     * Create context for a theme.
     *
     * @param int $themeId Theme id.
     *
     * @return static
     */
    public static function forTheme(int $themeId): self
    {
        return new static($themeId);
    }

    /**
     * Get themeId.
     *
     * @return int
     */
    public function getThemeId(): int
    {
        return $this->themeId;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return 'theme:' . $this->themeId;
    }
}
