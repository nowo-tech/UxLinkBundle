<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Util;

use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Util\PhoneNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Util\PhoneNormalizer
 */
final class PhoneNormalizerTest extends TestCase
{
    #[DataProvider('normalizeProvider')]
    public function testNormalize(string $input, string $expected): void
    {
        self::assertSame($expected, PhoneNormalizer::normalize($input));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function normalizeProvider(): iterable
    {
        yield 'e164 with plus' => ['+34 600 11 12 22', '+34600111222'];
        yield 'international 00' => ['0034 600 111 222', '+34600111222'];
        yield 'digits only local' => ['600111222', '600111222'];
    }

    public function testDigitsOnlyStripsPlus(): void
    {
        self::assertSame('34600111222', PhoneNormalizer::digitsOnly('+34 600 11 12 22'));
    }

    public function testEmptyPhoneThrows(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        PhoneNormalizer::normalize('   ');
    }
}
