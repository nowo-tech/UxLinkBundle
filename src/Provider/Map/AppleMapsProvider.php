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
 * Builds Apple Maps links.
 */
#[AsLinkProvider]
final class AppleMapsProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Map;
    }

    public function getName(): string
    {
        return 'apple_maps';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var MapOptions $options */
        $query = match ($options->action) {
            MapAction::Directions, MapAction::Route => array_filter([
                'saddr' => $options->origin,
                'daddr' => $options->destination ?? $this->locationQuery($options),
                'dirflg' => $this->transportFlag($options->transportMode),
            ]),
            default => array_filter([
                'q' => $options->label ?? $options->address ?? $this->coordsQuery($options),
                'll' => $this->hasCoords($options) ? $this->coordsQuery($options) : null,
            ]),
        };

        $url = UrlBuilder::build('https://maps.apple.com/', $query);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
            metadata: ['action' => $options->action->value],
        );
    }

    private function transportFlag(?string $mode): string
    {
        return match ($mode) {
            'walking' => 'w',
            'transit' => 'r',
            'bicycling' => 'c',
            default => 'd',
        };
    }

    private function hasCoords(MapOptions $options): bool
    {
        return null !== $options->latitude && null !== $options->longitude;
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
