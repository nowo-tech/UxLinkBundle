<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Security;

use Nowo\UxLinkBundle\Exception\InvalidUrlException;
use Nowo\UxLinkBundle\Security\UrlPolicy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Security\UrlPolicy
 */
final class UrlPolicyTest extends TestCase
{
    #[DataProvider('allowedUrlsProvider')]
    public function testAllowedUrls(string $url): void
    {
        self::assertTrue(UrlPolicy::isAllowedUserUrl($url));
        UrlPolicy::assertAllowedUserUrl($url);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function allowedUrlsProvider(): iterable
    {
        yield 'https' => ['https://example.com/path?q=1'];
        yield 'relative' => ['/assets/file.pdf'];
        yield 'mailto' => ['mailto:user@example.com'];
    }

    #[DataProvider('forbiddenUrlsProvider')]
    public function testForbiddenUrls(string $url): void
    {
        self::assertFalse(UrlPolicy::isAllowedUserUrl($url));
        $this->expectException(InvalidUrlException::class);
        UrlPolicy::assertAllowedUserUrl($url);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function forbiddenUrlsProvider(): iterable
    {
        yield 'javascript' => ['javascript:alert(1)'];
        yield 'data' => ['data:text/html,<script>alert(1)</script>'];
    }
}
