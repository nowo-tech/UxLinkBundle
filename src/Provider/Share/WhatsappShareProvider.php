<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Share;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;
use Nowo\UxLinkBundle\Util\UrlBuilder;

/**
 * Builds WhatsApp share links.
 */
#[AsLinkProvider]
final class WhatsappShareProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Share;
    }

    public function getName(): string
    {
        return 'whatsapp';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ShareOptions $options */
        $message = $options->text ?? $options->title ?? '';
        if ('' !== $message) {
            $message .= ' ';
        }
        $message .= $options->url;

        $url = UrlBuilder::build('https://wa.me/', ['text' => trim($message)]);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
