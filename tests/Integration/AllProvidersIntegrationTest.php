<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Exercises all v1 providers through the link factory.
 */
#[CoversNothing]
final class AllProvidersIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    /**
     * @param array<string, mixed> $options
     */
    #[DataProvider('providerCases')]
    public function testProviderCreatesExpectedUrl(string $family, string $provider, array $options, string $expectedSubstring): void
    {
        self::bootKernel();
        /** @var LinkFactoryInterface $factory */
        $factory = self::getContainer()->get(LinkFactoryInterface::class);
        $link = $factory->create($family, $provider, $options);

        self::assertStringContainsString($expectedSubstring, $link->url);
    }

    /**
     * @return iterable<string, array{string, string, array<string, mixed>, string}>
     */
    public static function providerCases(): iterable
    {
        yield 'contact email' => ['contact', 'email', ['recipient' => 'a@b.com', 'subject' => 'Hi'], 'mailto:a@b.com'];
        yield 'contact tel' => ['contact', 'telephone', ['recipient' => '+34600111222'], 'tel:+34600111222'];
        yield 'contact sms' => ['contact', 'sms', ['recipient' => '+34600111222', 'message' => 'Hi'], 'sms:+34600111222'];
        yield 'share linkedin' => ['share', 'linkedin', ['url' => 'https://example.com'], 'linkedin.com/sharing'];
        yield 'share x' => ['share', 'x', ['url' => 'https://example.com', 'text' => 'Hi'], 'twitter.com/intent/tweet'];
        yield 'share whatsapp' => ['share', 'whatsapp', ['url' => 'https://example.com'], 'wa.me'];
        yield 'share telegram' => ['share', 'telegram', ['url' => 'https://example.com'], 't.me/share/url'];
        yield 'share email' => ['share', 'email', ['url' => 'https://example.com', 'title' => 'T'], 'mailto:?'];
        yield 'map google' => ['map', 'google_maps', ['latitude' => 37.4, 'longitude' => -4.1], 'google.com/maps'];
        yield 'map apple' => ['map', 'apple_maps', ['latitude' => 37.4, 'longitude' => -4.1], 'maps.apple.com'];
        yield 'map waze' => ['map', 'waze', ['latitude' => 37.4, 'longitude' => -4.1], 'waze.com/ul'];
        yield 'map osm' => ['map', 'openstreetmap', ['latitude' => 37.4, 'longitude' => -4.1], 'openstreetmap.org'];
        yield 'download' => ['download', 'download', ['url' => '/files/doc.pdf', 'filename' => 'doc.pdf'], '/files/doc.pdf'];
    }
}
