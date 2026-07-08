<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Twig\Component;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Renders share links for the given providers.
 */
#[AsTwigComponent(
    name: 'UxShareLinks',
    template: '@NowoUxLinkBundle/components/ux-share-links.html.twig',
)]
final class UxShareLinks
{
    public LinkCollection $links;

    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * @param list<string> $providers
     */
    public function mount(
        string $url,
        ?string $title = null,
        ?string $text = null,
        array $providers = ['linkedin', 'x', 'whatsapp', 'telegram', 'email'],
    ): void {
        $options = array_filter([
            'url' => $url,
            'title' => $title,
            'text' => $text,
        ]);

        $this->links = $this->linkFactory->createMany('share', $providers, $options);
    }
}
