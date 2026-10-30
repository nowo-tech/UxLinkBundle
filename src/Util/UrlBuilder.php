<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Util;

/**
 * Safe URL and query-string construction.
 */
final class UrlBuilder
{
    /**
     * Appends a RFC 3986 encoded query string (null and empty values are dropped) and an encoded fragment to a base URL.
     *
     * @param string                     $base     Base URL (may already contain a query string)
     * @param array<string, scalar|null> $query
     * @param string|null                $fragment Fragment without `#`
     */
    public static function build(string $base, array $query = [], ?string $fragment = null): string
    {
        $url = $base;
        if ([] !== $query) {
            $filtered = [];
            foreach ($query as $key => $value) {
                if (null === $value || '' === $value) {
                    continue;
                }
                $filtered[$key] = (string) $value;
            }
            if ([] !== $filtered) {
                $separator = str_contains($base, '?') ? '&' : '?';
                $url .= $separator.http_build_query($filtered, '', '&', \PHP_QUERY_RFC3986);
            }
        }

        if (null !== $fragment && '' !== $fragment) {
            $url .= '#'.rawurlencode($fragment);
        }

        return $url;
    }

    /**
     * Percent-encodes a value for a `mailto:` header field (subject, body, cc).
     *
     * @param string $value Raw value
     */
    public static function encodeMailHeader(string $value): string
    {
        return rawurlencode($value);
    }
}
