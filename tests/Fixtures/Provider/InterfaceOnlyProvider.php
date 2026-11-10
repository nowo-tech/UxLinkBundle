<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Host-style provider that only implements the interface (no attribute, no explicit tag).
 */
final class InterfaceOnlyProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Share;
    }

    public function getName(): string
    {
        return 'mastodon';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        return new Link(LinkFamily::Share, 'mastodon', 'https://mastodon.example/share', label: 'Mastodon');
    }
}
