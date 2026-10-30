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

    /**
     * Creates the component.
     *
     * @param LinkFactoryInterface $linkFactory Factory building the link
     */
    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * Builds the link; the shortcut props override the matching keys of `options`.
     *
     * @param string               $family    Family value
     * @param string               $provider  Provider name or alias
     * @param array<string, mixed> $options
     * @param string|null          $label     Link label
     * @param string|null          $recipient Contact recipient (email or phone)
     * @param string|null          $message   Contact message
     * @param string|null          $url       Shared, map or download URL
     * @param string|null          $class     CSS classes appended to the link
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

        // @igor-ignore - Not shared worker service state.
        $this->link = $this->linkFactory->create($family, $provider, $options);
        if (null !== $class) {
            // @igor-ignore - Not shared worker service state.
            $this->link = $this->link->withAttributes(new LinkAttributes(class: $class));
        }
    }
}
