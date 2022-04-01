<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

final class ThemeContext extends AbstractContext
{
    /**
     * Theme id.
     */
    private int $themeId;

    /**
     * @param int $themeId Theme id.
     */
    private function __construct(int $themeId)
    {
        $this->themeId = $themeId;
    }

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
     */
    public function getThemeId(): int
    {
        return $this->themeId;
    }

    public function __toString(): string
    {
        return 'theme:' . $this->themeId;
    }
}
