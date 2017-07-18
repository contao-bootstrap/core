<?php

/**
 * @package    contao-bootstrap
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace ContaoBootstrap\Core\Environment;

/**
 * Class ThemeContext.
 *
 * @package ContaoBootstrap\Core\Config\Context
 */
class ThemeContext extends AbstractContext
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
    private function __construct($themeId)
    {
        $this->themeId = $themeId;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Context $context)
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
    public static function forTheme($themeId)
    {
        return new static($themeId);
    }

    /**
     * Get themeId.
     *
     * @return int
     */
    public function getThemeId()
    {
        return $this->themeId;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'theme:' . $this->themeId;
    }
}
