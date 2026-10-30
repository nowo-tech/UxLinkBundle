<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Security;

/**
 * Whitelist for HTML attributes on rendered links.
 */
final class HtmlAttributePolicy
{
    /** @var list<string> */
    private const ALLOWED = [
        'class',
        'id',
        'title',
        'target',
        'rel',
        'download',
        'aria-label',
        'aria-describedby',
        'aria-hidden',
        'role',
        'lang',
        'hreflang',
    ];

    /**
     * Keeps only the attributes allowed by {@see isAllowed()}.
     *
     * @param array<string, string> $attributes
     *
     * @return array<string, string>
     */
    public static function filter(array $attributes): array
    {
        $filtered = [];
        foreach ($attributes as $name => $value) {
            if (!self::isAllowed($name)) {
                continue;
            }
            $filtered[$name] = $value;
        }

        return $filtered;
    }

    /**
     * Whether an attribute name is on the allow-list or is a `data-*` attribute.
     *
     * @param string $name Attribute name
     */
    public static function isAllowed(string $name): bool
    {
        if (\in_array($name, self::ALLOWED, true)) {
            return true;
        }

        return (bool) preg_match('/^data-[a-z0-9_-]+$/', $name);
    }

    /**
     * Blanks `javascript:` values and returns any other value unchanged.
     *
     * @param string $value Attribute value
     */
    public static function sanitizeValue(string $value): string
    {
        if (str_starts_with(strtolower(trim($value)), 'javascript:')) {
            return '';
        }

        return $value;
    }
}
