<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

use Nowo\UxLinkBundle\Enum\LinkAvailability;
use Nowo\UxLinkBundle\Enum\LinkFamily;

/**
 * Normalized link value object.
 */
final readonly class Link implements \Stringable
{
    /**
     * Creates a link value object.
     *
     * @param array<string, scalar|null> $metadata
     */
    public function __construct(
        public LinkFamily $family,
        public string $provider,
        public string $url,
        public LinkAvailability $availability = LinkAvailability::Available,
        public ?string $label = null,
        public ?string $icon = null,
        public LinkAttributes $attributes = new LinkAttributes(),
        public array $metadata = [],
    ) {
    }

    /**
     * Returns the generated URL.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns the generated URL (lets a link be printed directly in Twig).
     */
    public function __toString(): string
    {
        return $this->url;
    }

    /**
     * Returns a copy of the link with another label.
     *
     * @param string|null $label New label (null lets the defaults apply)
     */
    public function withLabel(?string $label): self
    {
        return new self(
            family: $this->family,
            provider: $this->provider,
            url: $this->url,
            availability: $this->availability,
            label: $label,
            icon: $this->icon,
            attributes: $this->attributes,
            metadata: $this->metadata,
        );
    }

    /**
     * Returns a copy of the link with the given attributes merged over the current ones.
     *
     * @param LinkAttributes $attributes Attributes to merge (non-null values win, classes are appended)
     */
    public function withAttributes(LinkAttributes $attributes): self
    {
        return new self(
            family: $this->family,
            provider: $this->provider,
            url: $this->url,
            availability: $this->availability,
            label: $this->label,
            icon: $this->icon,
            attributes: $this->attributes->merge($attributes),
            metadata: $this->metadata,
        );
    }

    /**
     * Returns a copy of the link with another icon.
     *
     * @param string|null $icon Logical icon name, or null for none
     */
    public function withIcon(?string $icon): self
    {
        return new self(
            family: $this->family,
            provider: $this->provider,
            url: $this->url,
            availability: $this->availability,
            label: $this->label,
            icon: $icon,
            attributes: $this->attributes,
            metadata: $this->metadata,
        );
    }
}
