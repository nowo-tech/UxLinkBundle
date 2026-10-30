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

    /**
     * Creates the component.
     *
     * @param LinkFactoryInterface $linkFactory Factory building the share links
     */
    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * Builds the share links for the URL (empty title/text are dropped).
     *
     * @param string       $url       URL to share
     * @param string|null  $title     Title
     * @param string|null  $text      Text
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

        // @igor-ignore - Not shared worker service state.
        $this->links = $this->linkFactory->createMany('share', $providers, $options);
    }
}
