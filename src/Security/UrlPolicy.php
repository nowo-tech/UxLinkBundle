<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Security;

use Nowo\UxLinkBundle\Exception\InvalidUrlException;

/**
 * URL scheme and user-supplied URL validation.
 */
final class UrlPolicy
{
    /** @var list<string> */
    private const ALLOWED_SCHEMES = [
        'http',
        'https',
        'mailto',
        'tel',
        'sms',
        'geo',
    ];

    /** @var list<string> */
    private const FORBIDDEN_SCHEMES = [
        'javascript',
        'data',
        'vbscript',
        'file',
    ];

    public static function assertAllowedUserUrl(string $url): void
    {
        if (!self::isAllowedUserUrl($url)) {
            throw new InvalidUrlException(\sprintf('URL scheme is not allowed: %s', self::extractScheme($url) ?? 'unknown'));
        }
    }

    public static function isAllowedUserUrl(string $url): bool
    {
        $scheme = self::extractScheme($url);
        if (null === $scheme) {
            return self::isRelativePath($url);
        }

        if (\in_array($scheme, self::FORBIDDEN_SCHEMES, true)) {
            return false;
        }

        return \in_array($scheme, self::ALLOWED_SCHEMES, true);
    }

    public static function isExternalHttpUrl(string $url): bool
    {
        $scheme = self::extractScheme($url);

        return 'http' === $scheme || 'https' === $scheme;
    }

    private static function extractScheme(string $url): ?string
    {
        if (!preg_match('#^([a-z][a-z0-9+.-]*):#i', $url, $matches)) {
            return null;
        }

        return strtolower($matches[1]);
    }

    /**
     * Same-origin absolute path only. Rejects protocol-relative `//host`, backslash variants
     * (`/\host`, which browsers normalise to `//host`) and control characters (browsers strip
     * tabs/newlines, so `/<TAB>/host` would also become `//host`).
     */
    private static function isRelativePath(string $url): bool
    {
        if (!str_starts_with($url, '/')) {
            return false;
        }

        if (1 === preg_match('/[\x00-\x1F\x7F]/', $url)) {
            return false;
        }

        $second = $url[1] ?? '';

        return '/' !== $second && '\\' !== $second;
    }
}
