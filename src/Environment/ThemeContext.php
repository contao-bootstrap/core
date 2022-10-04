<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

final class ThemeContext extends AbstractContext
{
    /** @param int $themeId Theme id. */
    private function __construct(private readonly int $themeId)
    {
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
        return new self($themeId);
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
