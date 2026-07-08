<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Registry;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\ProviderNotFoundException;

/**
 * Registry of link providers indexed by family and name.
 */
final class LinkProviderRegistry
{
    /**
     * @var array<string, array<string, LinkProviderInterface>>
     */
    private array $providers = [];

    /**
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
            $this->add($provider, $priority);
        }
    }

    public function add(LinkProviderInterface $provider, int $priority = 0): void
    {
        $family = $provider->getFamily()->value;
        $name = $provider->getName();

        if (!isset($this->providers[$family])) {
            $this->providers[$family] = [];
        }

        $existing = $this->providers[$family][$name] ?? null;
        if (null !== $existing && $priority <= 0) {
            return;
        }

        $this->providers[$family][$name] = $provider;
    }

    public function get(LinkFamily $family, string $provider): LinkProviderInterface
    {
        $resolved = $this->providers[$family->value][$provider] ?? null;
        if (null === $resolved) {
            throw new ProviderNotFoundException(\sprintf('No link provider registered for family "%s" and provider "%s".', $family->value, $provider));
        }

        return $resolved;
    }

    public function has(LinkFamily $family, string $provider): bool
    {
        return isset($this->providers[$family->value][$provider]);
    }

    /**
     * @return list<LinkProviderInterface>
     */
    public function all(LinkFamily $family): array
    {
        return array_values($this->providers[$family->value] ?? []);
    }
}
