<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Contact;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;
use Nowo\UxLinkBundle\Util\PhoneNormalizer;
use Nowo\UxLinkBundle\Util\UrlBuilder;

/**
 * Builds WhatsApp wa.me links (no API calls).
 */
#[AsLinkProvider]
final class WhatsappContactProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Contact;
    }

    public function getName(): string
    {
        return 'whatsapp';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ContactOptions $options */
        $phone = PhoneNormalizer::digitsOnly($options->recipient);
        $url = UrlBuilder::build(
            'https://wa.me/'.$phone,
            ['text' => $options->message],
        );

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
