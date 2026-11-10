<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Base for host-style contact providers that reuse a built-in name; the subclass decides how it is registered.
 */
abstract class HostContactProvider extends AbstractLinkProvider
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
        return new Link(LinkFamily::Contact, $this->getName(), 'mailto:host@example.com', label: 'Host');
    }
}
