<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Util;

/**
 * Safe URL and query-string construction.
 */
final class UrlBuilder
{
    /**
     * @param array<string, scalar|null> $query
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

    public static function encodeMailHeader(string $value): string
    {
        return rawurlencode($value);
    }
}
