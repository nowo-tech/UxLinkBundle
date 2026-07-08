<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Twig\Component;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Renders a single UX link.
 */
#[AsTwigComponent(
    name: 'UxLink',
    template: '@NowoUxLinkBundle/components/ux-link-component.html.twig',
)]
final class UxLink
{
    public Link $link;

    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     */
    public function mount(
        string $family,
        string $provider,
        array $options = [],
        ?string $label = null,
        ?string $recipient = null,
        ?string $message = null,
        ?string $url = null,
        ?string $class = null,
    ): void {
        if (null !== $recipient) {
            $options['recipient'] = $recipient;
        }
        if (null !== $message) {
            $options['message'] = $message;
        }
        if (null !== $url) {
            $options['url'] = $url;
        }
        if (null !== $label) {
            $options['label'] = $label;
        }

        $this->link = $this->linkFactory->create($family, $provider, $options);
        if (null !== $class) {
            $this->link = $this->link->withAttributes(new LinkAttributes(class: $class));
        }
    }
}
