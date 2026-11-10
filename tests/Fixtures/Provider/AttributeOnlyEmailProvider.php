<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Host-style provider registered only through #[AsLinkProvider] (no explicit tag); replaces contact/email.
 */
#[AsLinkProvider(priority: 10)]
final class AttributeOnlyEmailProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Contact;
    }

    public function getName(): string
    {
        return 'email';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        return new Link(LinkFamily::Contact, 'email', 'mailto:host@example.com', label: 'Host');
    }
}
