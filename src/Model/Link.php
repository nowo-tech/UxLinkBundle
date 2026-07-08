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

    public function getUrl(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

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
