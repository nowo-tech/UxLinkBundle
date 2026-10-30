<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Registry;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\ProviderNotFoundException;

/**
 * Registry of link providers indexed by family and name.
 *
 * Providers are registered only at construction (from tagged services). The map is
 * immutable afterward so FrankenPHP worker mode with kernel reset disabled cannot
 * leak providers across requests via runtime mutation.
 */
final class LinkProviderRegistry
{
    /**
     * @var array<string, array<string, LinkProviderInterface>>
     */
    private array $providers = [];

    /**
     * Registers every tagged provider once, honouring the configured `priority` for duplicates.
     *
     * @param iterable<LinkProviderInterface> $providers
     */
    public function __construct(
        iterable $providers,
        private readonly BundleConfiguration $configuration,
    ) {
        foreach ($providers as $provider) {
            $family = $provider->getFamily()->value;
            $name = $provider->getName();
            $priority = (int) ($this->configuration->providerConfig($family, $name)['priority'] ?? 0);
            $this->register($provider, $priority);
        }
    }

    /**
     * Returns the provider registered for the family and name.
     *
     * @param LinkFamily $family   Link family
     * @param string     $provider Provider name
     *
     * @throws ProviderNotFoundException When no provider is registered
     */
    public function get(LinkFamily $family, string $provider): LinkProviderInterface
    {
        $resolved = $this->providers[$family->value][$provider] ?? null;
        if (null === $resolved) {
            throw new ProviderNotFoundException(\sprintf('No link provider registered for family "%s" and provider "%s".', $family->value, $provider));
        }

        return $resolved;
    }

    /**
     * Whether a provider is registered for the family and name.
     *
     * @param LinkFamily $family   Link family
     * @param string     $provider Provider name
     */
    public function has(LinkFamily $family, string $provider): bool
    {
        return isset($this->providers[$family->value][$provider]);
    }

    /**
     * Returns every provider registered for the family.
     *
     * @return list<LinkProviderInterface>
     */
    public function all(LinkFamily $family): array
    {
        return array_values($this->providers[$family->value] ?? []);
    }

    private function register(LinkProviderInterface $provider, int $priority = 0): void
    {
        $family = $provider->getFamily()->value;
        $name = $provider->getName();

        if (!isset($this->providers[$family])) {
            // @igor-ignore - Not shared worker service state.
            $this->providers[$family] = [];
        }

        $existing = $this->providers[$family][$name] ?? null;
        if (null !== $existing && $priority <= 0) {
            return;
        }

        // @igor-ignore - Not shared worker service state.
        $this->providers[$family][$name] = $provider;
    }
}
