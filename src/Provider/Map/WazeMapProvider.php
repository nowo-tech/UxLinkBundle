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
 * Builds Waze navigation links.
 */
#[AsLinkProvider]
final class WazeMapProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Map;
    }

    public function getName(): string
    {
        return 'waze';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var MapOptions $options */
        $query = array_filter([
            'll' => $this->hasCoords($options) ? $this->coordsQuery($options) : null,
            'q' => $options->address,
            'navigate' => MapAction::Directions === $options->action || MapAction::Route === $options->action ? 'yes' : null,
        ]);

        $url = UrlBuilder::build('https://waze.com/ul', $query);

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
