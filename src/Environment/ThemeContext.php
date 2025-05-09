<?php

declare(strict_types=1);

namespace ContaoBootstrap\Core\Environment;

use Override;

final class ThemeContext extends AbstractContext
{
    /** @param int $themeId Theme id. */
    private function __construct(public readonly int $themeId)
    {
    }

    #[Override]
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

    #[Override]
    public function toString(): string
    {
        return 'theme:' . $this->themeId;
    }
}
