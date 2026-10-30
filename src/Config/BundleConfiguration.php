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
     * Holds the processed `nowo_ux_link` configuration sections.
     *
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
     * Builds the runtime configuration from the processed bundle configuration array (missing sections default to empty).
     *
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

    /**
     * Whether a link family is enabled (families are enabled unless configured with `enabled: false`).
     *
     * @param string $family Family value (e.g. `contact`, `share`)
     */
    public function isFamilyEnabled(string $family): bool
    {
        return ($this->families[$family]['enabled'] ?? true) === true;
    }

    /**
     * Whether a provider of a family is enabled (providers are enabled unless configured with `enabled: false`).
     *
     * @param string $family   Family value (e.g. `share`)
     * @param string $provider Provider name (e.g. `linkedin`)
     */
    public function isProviderEnabled(string $family, string $provider): bool
    {
        return ($this->providers[$family][$provider]['enabled'] ?? true) === true;
    }

    /**
     * Resolves a configured provider alias to the real provider name (returns the name unchanged when no alias exists).
     *
     * @param string $family   Family value
     * @param string $provider Provider name or alias
     *
     * @return string Real provider name
     */
    public function resolveProviderAlias(string $family, string $provider): string
    {
        return $this->aliases[$family][$provider] ?? $provider;
    }

    /**
     * Returns the per-provider configuration (`enabled`, `icon`, `label`, `priority`), or an empty array when not configured.
     *
     * @return array<string, mixed>
     */
    public function providerConfig(string $family, string $provider): array
    {
        return $this->providers[$family][$provider] ?? [];
    }

    /**
     * Returns the configured default `target` attribute for external links, or null when not set.
     */
    public function defaultTarget(): ?string
    {
        $target = $this->defaults['target'] ?? null;

        return \is_string($target) ? $target : null;
    }

    /**
     * Returns the configured default `rel` attribute for external links, or null when not set.
     */
    public function defaultRel(): ?string
    {
        $rel = $this->defaults['rel'] ?? null;

        return \is_string($rel) ? $rel : null;
    }

    /**
     * Whether icons are shown by default (`defaults.show_icons`, true unless disabled).
     */
    public function showIcons(): bool
    {
        return ($this->defaults['show_icons'] ?? true) === true;
    }

    /**
     * Returns the policy deciding when default `target`/`rel` attributes are applied (falls back to `auto` for unknown values).
     */
    public function externalTargetPolicy(): ExternalTargetPolicy
    {
        $policy = $this->defaults['external_target_policy'] ?? ExternalTargetPolicy::Auto->value;

        return ExternalTargetPolicy::tryFrom((string) $policy) ?? ExternalTargetPolicy::Auto;
    }
}
