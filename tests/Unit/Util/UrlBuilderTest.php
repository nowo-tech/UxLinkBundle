<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Util;

use Nowo\UxLinkBundle\Util\UrlBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Util\UrlBuilder
 */
final class UrlBuilderTest extends TestCase
{
    public function testBuildsQueryWithRfc3986Encoding(): void
    {
        $url = UrlBuilder::build('https://example.com', ['q' => 'hello world', 'empty' => '']);

        self::assertSame('https://example.com?q=hello%20world', $url);
    }

    public function testAppendsToExistingQuery(): void
    {
        $url = UrlBuilder::build('https://example.com?a=1', ['b' => '2']);

        self::assertSame('https://example.com?a=1&b=2', $url);
    }
}
