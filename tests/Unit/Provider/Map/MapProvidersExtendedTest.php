<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Provider\Map;

use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Enum\MapAction;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Provider\Map\AppleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\GoogleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\OpenStreetMapProvider;
use Nowo\UxLinkBundle\Provider\Map\WazeMapProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Provider\Map\GoogleMapsProvider
 * @covers \Nowo\UxLinkBundle\Provider\Map\AppleMapsProvider
 * @covers \Nowo\UxLinkBundle\Provider\Map\WazeMapProvider
 * @covers \Nowo\UxLinkBundle\Provider\Map\OpenStreetMapProvider
 */
final class MapProvidersExtendedTest extends TestCase
{
    #[DataProvider('mapCases')]
    public function testMapActions(string $providerClass, MapOptions $options, string $needle): void
    {
        /** @var LinkProviderInterface $provider */
        $provider = new $providerClass();
        $link = $provider->create($options);

        self::assertStringContainsString($needle, $link->url);
    }

    /**
     * @return iterable<string, array{class-string, MapOptions, string}>
     */
    public static function mapCases(): iterable
    {
        yield 'google directions' => [GoogleMapsProvider::class, new MapOptions(origin: 'A', destination: 'B', action: MapAction::Route), 'google.com/maps/dir'];
        yield 'google search address' => [GoogleMapsProvider::class, new MapOptions(address: 'Madrid', action: MapAction::Search), 'google.com/maps/search'];
        yield 'apple walking' => [AppleMapsProvider::class, new MapOptions(origin: 'A', destination: 'B', transportMode: 'walking', action: MapAction::Route), 'maps.apple.com'];
        yield 'waze navigate' => [WazeMapProvider::class, new MapOptions(latitude: 1.0, longitude: 2.0, action: MapAction::Directions), 'navigate=yes'];
        yield 'osm address search' => [OpenStreetMapProvider::class, new MapOptions(address: 'Berlin'), 'openstreetmap.org/search'];
    }
}
