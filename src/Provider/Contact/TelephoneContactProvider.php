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

/**
 * Builds tel: contact links.
 */
#[AsLinkProvider]
final class TelephoneContactProvider extends AbstractLinkProvider
{
    /**
     * Returns the contact family.
     */
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Contact;
    }

    /**
     * Returns the provider name (`telephone`).
     */
    public function getName(): string
    {
        return 'telephone';
    }

    /**
     * Builds tel: contact links from the contact options.
     *
     * @param LinkOptionsInterface $options ContactOptions instance
     */
    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ContactOptions $options */
        $phone = PhoneNormalizer::normalize($options->recipient);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: 'tel:'.$phone,
            label: $options->label,
        );
    }
}
