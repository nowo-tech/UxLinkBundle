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
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Contact;
    }

    public function getName(): string
    {
        return 'telephone';
    }

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
