<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Twig\Component;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Renders a collection of UX links.
 */
#[AsTwigComponent(
    name: 'UxLinks',
    template: '@NowoUxLinkBundle/components/ux-links.html.twig',
)]
final class UxLinks
{
    public LinkCollection $links;

    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * @param list<string>         $providers
     * @param array<string, mixed> $options
     */
    public function mount(string $family, array $providers, array $options = []): void
    {
        $this->links = $this->linkFactory->createMany($family, $providers, $options);
    }
}
