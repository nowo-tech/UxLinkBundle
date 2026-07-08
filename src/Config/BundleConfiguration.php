<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Config;

use Nowo\UxLinkBundle\Enum\ExternalTargetPolicy;

/**
 * Resolved bundle configuration for runtime services.
 */
final readonly class BundleConfiguration
{
    /**
     * @param array<string, mixed>                               $defaults
     * @param array<string, array<string, mixed>>                $families
     * @param array<string, array<string, array<string, mixed>>> $providers
     * @param array<string, array<string, string>>               $aliases
     */
    public function __construct(
        public array $defaults,
        public array $families,
        public array $providers,
        public array $aliases,
    ) {
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function fromArray(array $config): self
    {
        return new self(
            defaults: $config['defaults'] ?? [],
            families: $config['families'] ?? [],
            providers: $config['providers'] ?? [],
            aliases: $config['aliases'] ?? [],
        );
    }

    public function isFamilyEnabled(string $family): bool
    {
        return ($this->families[$family]['enabled'] ?? true) === true;
    }

    public function isProviderEnabled(string $family, string $provider): bool
    {
        return ($this->providers[$family][$provider]['enabled'] ?? true) === true;
    }

    public function resolveProviderAlias(string $family, string $provider): string
    {
        return $this->aliases[$family][$provider] ?? $provider;
    }

    /**
     * @return array<string, mixed>
     */
    public function providerConfig(string $family, string $provider): array
    {
        return $this->providers[$family][$provider] ?? [];
    }

    public function defaultTarget(): ?string
    {
        $target = $this->defaults['target'] ?? null;

        return \is_string($target) ? $target : null;
    }

    public function defaultRel(): ?string
    {
        $rel = $this->defaults['rel'] ?? null;

        return \is_string($rel) ? $rel : null;
    }

    public function showIcons(): bool
    {
        return ($this->defaults['show_icons'] ?? true) === true;
    }

    public function externalTargetPolicy(): ExternalTargetPolicy
    {
        $policy = $this->defaults['external_target_policy'] ?? ExternalTargetPolicy::Auto->value;

        return ExternalTargetPolicy::tryFrom((string) $policy) ?? ExternalTargetPolicy::Auto;
    }
}
