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
 * Builds sms: contact links.
 */
#[AsLinkProvider]
final class SmsContactProvider extends AbstractLinkProvider
{
    /**
     * Returns the contact family.
     */
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Contact;
    }

    /**
     * Returns the provider name (`sms`).
     */
    public function getName(): string
    {
        return 'sms';
    }

    /**
     * Builds sms: contact links from the contact options.
     *
     * @param LinkOptionsInterface $options ContactOptions instance
     */
    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ContactOptions $options */
        $phone = PhoneNormalizer::normalize($options->recipient);
        $url = UrlBuilder::build('sms:'.$phone, ['body' => $options->message]);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
