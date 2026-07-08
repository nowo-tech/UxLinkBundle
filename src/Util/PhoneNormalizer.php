<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Util;

use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;

/**
 * Lightweight phone normalization for tel:, sms:, and wa.me links.
 */
final class PhoneNormalizer
{
    private const MIN_DIGITS = 7;

    /**
     * Normalizes a phone number to E.164-like form (+ and digits).
     *
     * @return non-empty-string
     */
    public static function normalize(string $phone): string
    {
        $trimmed = trim($phone);
        if ('' === $trimmed) {
            throw new InvalidLinkOptionsException('Phone number cannot be empty.');
        }

        $hasPlus = str_starts_with($trimmed, '+');
        $digits = preg_replace('/\D+/', '', $trimmed) ?? '';
        if ('' === $digits) {
            throw new InvalidLinkOptionsException('Phone number must contain digits.');
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $hasPlus = true;
        }

        if (\strlen($digits) < self::MIN_DIGITS) {
            throw new InvalidLinkOptionsException('Phone number is too short.');
        }

        return $hasPlus ? '+'.$digits : $digits;
    }

    /**
     * Digits only (no plus) for wa.me URLs.
     *
     * @return non-empty-string
     */
    public static function digitsOnly(string $phone): string
    {
        $normalized = self::normalize($phone);
        $digits = str_starts_with($normalized, '+') ? substr($normalized, 1) : $normalized;
        \assert('' !== $digits);

        return $digits;
    }
}
