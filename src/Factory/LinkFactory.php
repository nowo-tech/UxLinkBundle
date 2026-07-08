<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Factory;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Contract\IconResolverInterface;
use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Enum\ExternalTargetPolicy;
use Nowo\UxLinkBundle\Exception\DisabledProviderException;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Security\UrlPolicy;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Creates link models and applies bundle defaults.
 */
final class LinkFactory implements LinkFactoryInterface
{
    private const TRANSLATION_DOMAIN = 'NowoUxLinkBundle';

    public function __construct(
        private readonly LinkProviderRegistry $registry,
        private readonly OptionsFactory $optionsFactory,
        private readonly BundleConfiguration $configuration,
        private readonly IconResolverInterface $iconResolver,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function create(string $family, string $provider, array $options = []): Link
    {
        $familyEnum = $this->optionsFactory->tryParseFamily($family);
        if (!$this->configuration->isFamilyEnabled($familyEnum->value)) {
            throw new DisabledProviderException(\sprintf('Link family "%s" is disabled.', $family));
        }

        $provider = strtolower($provider);
        $provider = $this->configuration->resolveProviderAlias($familyEnum->value, $provider);

        if (!$this->configuration->isProviderEnabled($familyEnum->value, $provider)) {
            throw new DisabledProviderException(\sprintf('Provider "%s" for family "%s" is disabled.', $provider, $familyEnum->value));
        }

        $typedOptions = $this->optionsFactory->create($familyEnum, $options);
        $linkProvider = $this->registry->get($familyEnum, $provider);
        $link = $linkProvider->create($typedOptions);

        return $this->applyDefaults($link);
    }

    public function createMany(string $family, array $providers, array $options = []): LinkCollection
    {
        $collection = new LinkCollection();
        foreach ($providers as $provider) {
            $collection = $collection->add($this->create($family, $provider, $options));
        }

        return $collection;
    }

    private function applyDefaults(Link $link): Link
    {
        $providerConfig = $this->configuration->providerConfig($link->family->value, $link->provider);

        $label = $link->label;
        if (null === $label) {
            $configLabel = $providerConfig['label'] ?? null;
            if (\is_string($configLabel) && '' !== $configLabel) {
                $label = $configLabel;
            } else {
                $label = $this->translator->trans(
                    \sprintf('%s.%s.label', $link->family->value, $link->provider),
                    [],
                    self::TRANSLATION_DOMAIN,
                );
            }
        }

        $icon = $link->icon;
        if (null === $icon) {
            $configIcon = $providerConfig['icon'] ?? null;
            $icon = \is_string($configIcon) && '' !== $configIcon ? $configIcon : $this->iconResolver->resolve($link);
        }

        $attributes = $this->buildDefaultAttributes($link);

        return new Link(
            family: $link->family,
            provider: $link->provider,
            url: $link->url,
            availability: $link->availability,
            label: $label,
            icon: $icon,
            attributes: $link->attributes->merge($attributes),
            metadata: $link->metadata,
        );
    }

    private function buildDefaultAttributes(Link $link): LinkAttributes
    {
        $target = $this->configuration->defaultTarget();
        $rel = $this->configuration->defaultRel();
        $policy = $this->configuration->externalTargetPolicy();
        $isExternal = UrlPolicy::isExternalHttpUrl($link->url);

        return match ($policy) {
            ExternalTargetPolicy::Never => new LinkAttributes(),
            ExternalTargetPolicy::Always => new LinkAttributes(
                target: $target ?? '_blank',
                rel: $rel ?? 'noopener noreferrer',
            ),
            ExternalTargetPolicy::Auto => $isExternal
                ? new LinkAttributes(target: $target ?? '_blank', rel: $rel ?? 'noopener noreferrer')
                : new LinkAttributes(),
        };
    }
}
