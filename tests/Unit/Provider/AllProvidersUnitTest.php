<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Provider;

use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\DownloadOptions;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use Nowo\UxLinkBundle\Provider\Contact\EmailContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\SmsContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\TelephoneContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Provider\Download\GenericDownloadProvider;
use Nowo\UxLinkBundle\Provider\Map\AppleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\GoogleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\OpenStreetMapProvider;
use Nowo\UxLinkBundle\Provider\Map\WazeMapProvider;
use Nowo\UxLinkBundle\Provider\Share\EmailShareProvider;
use Nowo\UxLinkBundle\Provider\Share\LinkedInShareProvider;
use Nowo\UxLinkBundle\Provider\Share\TelegramShareProvider;
use Nowo\UxLinkBundle\Provider\Share\WhatsappShareProvider;
use Nowo\UxLinkBundle\Provider\Share\XShareProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Smoke tests for every bundled provider.
 */
final class AllProvidersUnitTest extends TestCase
{
    /**
     * @param class-string<LinkProviderInterface> $class
     */
    #[DataProvider('providerCases')]
    public function testProviderCreatesUrl(string $class, LinkOptionsInterface $options, string $needle): void
    {
        /** @var LinkProviderInterface $provider */
        $provider = new $class();
        $link = $provider->create($options);

        self::assertStringContainsString($needle, $link->url);
        self::assertTrue($provider->supports($provider->getFamily(), $provider->getName()));
    }

    /**
     * @return iterable<string, array{class-string<LinkProviderInterface>, LinkOptionsInterface, string}>
     */
    public static function providerCases(): iterable
    {
        yield 'whatsapp contact' => [WhatsappContactProvider::class, new ContactOptions('+34600111222', 'Hi'), 'wa.me'];
        yield 'email contact' => [EmailContactProvider::class, new ContactOptions('a@b.com', subject: 'S'), 'mailto:a@b.com'];
        yield 'telephone' => [TelephoneContactProvider::class, new ContactOptions('+34600111222'), 'tel:'];
        yield 'sms' => [SmsContactProvider::class, new ContactOptions('+34600111222', 'Hi'), 'sms:'];
        yield 'linkedin' => [LinkedInShareProvider::class, new ShareOptions('https://example.com'), 'linkedin.com'];
        yield 'x' => [XShareProvider::class, new ShareOptions('https://example.com', text: 'Hi'), 'twitter.com'];
        yield 'share whatsapp' => [WhatsappShareProvider::class, new ShareOptions('https://example.com'), 'wa.me'];
        yield 'telegram' => [TelegramShareProvider::class, new ShareOptions('https://example.com'), 't.me'];
        yield 'share email' => [EmailShareProvider::class, new ShareOptions('https://example.com', title: 'T'), 'mailto:'];
        yield 'google maps' => [GoogleMapsProvider::class, MapOptions::fromArray(['latitude' => 1.0, 'longitude' => 2.0]), 'google.com'];
        yield 'apple maps' => [AppleMapsProvider::class, MapOptions::fromArray(['latitude' => 1.0, 'longitude' => 2.0]), 'maps.apple.com'];
        yield 'waze' => [WazeMapProvider::class, MapOptions::fromArray(['latitude' => 1.0, 'longitude' => 2.0]), 'waze.com'];
        yield 'osm' => [OpenStreetMapProvider::class, MapOptions::fromArray(['latitude' => 1.0, 'longitude' => 2.0]), 'openstreetmap.org'];
        yield 'download' => [GenericDownloadProvider::class, new DownloadOptions('/doc.pdf', filename: 'doc.pdf'), '/doc.pdf'];
    }
}
