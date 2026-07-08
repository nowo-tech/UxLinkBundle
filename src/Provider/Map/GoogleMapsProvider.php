<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Map;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Enum\MapAction;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;
use Nowo\UxLinkBundle\Util\UrlBuilder;

/**
 * Builds Google Maps links.
 */
#[AsLinkProvider]
final class GoogleMapsProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Map;
    }

    public function getName(): string
    {
        return 'google_maps';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var MapOptions $options */
        $url = match ($options->action) {
            MapAction::Directions => UrlBuilder::build(
                'https://www.google.com/maps/dir/',
                array_filter([
                    'api' => 1,
                    'origin' => $options->origin,
                    'destination' => $options->destination ?? $this->locationQuery($options),
                    'travelmode' => $options->transportMode,
                ]),
            ),
            MapAction::Route => UrlBuilder::build(
                'https://www.google.com/maps/dir/',
                array_filter([
                    'api' => 1,
                    'origin' => $options->origin,
                    'destination' => $options->destination,
                    'travelmode' => $options->transportMode,
                ]),
            ),
            MapAction::Search => UrlBuilder::build(
                'https://www.google.com/maps/search/',
                ['api' => 1, 'query' => $options->address ?? $this->coordsQuery($options)],
            ),
            MapAction::View => UrlBuilder::build(
                'https://www.google.com/maps/search/',
                ['api' => 1, 'query' => $this->locationQuery($options)],
            ),
        };

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
            metadata: ['action' => $options->action->value],
        );
    }

    private function locationQuery(MapOptions $options): string
    {
        if (null !== $options->address && '' !== $options->address) {
            return $options->address;
        }

        return $this->coordsQuery($options);
    }

    private function coordsQuery(MapOptions $options): string
    {
        return \sprintf('%s,%s', $options->latitude, $options->longitude);
    }
}
