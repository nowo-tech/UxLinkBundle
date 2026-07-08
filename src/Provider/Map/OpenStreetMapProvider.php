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
 * Builds OpenStreetMap links.
 */
#[AsLinkProvider]
final class OpenStreetMapProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Map;
    }

    public function getName(): string
    {
        return 'openstreetmap';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var MapOptions $options */
        if ($this->hasCoords($options) && MapAction::View === $options->action) {
            $url = \sprintf(
                'https://www.openstreetmap.org/?mlat=%s&mlon=%s#map=15/%s/%s',
                $options->latitude,
                $options->longitude,
                $options->latitude,
                $options->longitude,
            );
        } else {
            $url = UrlBuilder::build(
                'https://www.openstreetmap.org/search',
                ['query' => $options->address ?? $this->coordsQuery($options)],
            );
        }

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
            metadata: ['action' => $options->action->value],
        );
    }

    private function hasCoords(MapOptions $options): bool
    {
        return null !== $options->latitude && null !== $options->longitude;
    }

    private function coordsQuery(MapOptions $options): string
    {
        return \sprintf('%s,%s', $options->latitude, $options->longitude);
    }
}
