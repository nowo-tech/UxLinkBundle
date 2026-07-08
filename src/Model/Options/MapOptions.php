<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model\Options;

use Nowo\UxLinkBundle\Enum\MapAction;
use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;

/**
 * Map link options.
 */
final readonly class MapOptions implements LinkOptionsInterface
{
    public function __construct(
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $address = null,
        public ?string $label = null,
        public ?string $origin = null,
        public ?string $destination = null,
        public ?string $transportMode = null,
        public MapAction $action = MapAction::View,
    ) {
        if (null === $this->latitude && null === $this->longitude && (null === $this->address || '' === $this->address)) {
            if (MapAction::Route === $this->action && null !== $this->origin && null !== $this->destination) {
                return;
            }

            throw new InvalidLinkOptionsException('Map options require coordinates or an address.');
        }
    }

    public static function fromArray(array $data): static
    {
        $action = MapAction::View;
        if (isset($data['action']) && \is_string($data['action'])) {
            $action = MapAction::tryFrom($data['action']) ?? MapAction::View;
        }

        return new self(
            latitude: self::toFloat($data['latitude'] ?? null),
            longitude: self::toFloat($data['longitude'] ?? null),
            address: isset($data['address']) && \is_string($data['address']) ? $data['address'] : null,
            label: isset($data['label']) && \is_string($data['label']) ? $data['label'] : null,
            origin: isset($data['origin']) && \is_string($data['origin']) ? $data['origin'] : null,
            destination: isset($data['destination']) && \is_string($data['destination']) ? $data['destination'] : null,
            transportMode: isset($data['transportMode']) && \is_string($data['transportMode']) ? $data['transportMode'] : null,
            action: $action,
        );
    }

    private static function toFloat(mixed $value): ?float
    {
        if (\is_float($value) || \is_int($value)) {
            return (float) $value;
        }

        return null;
    }
}
