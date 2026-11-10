<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;

/**
 * Concrete provider found by resource loading (like a scan of the application namespace) and registered through the attribute.
 */
#[AsLinkProvider]
final class ScannedMastodonProvider extends AbstractAttributedProvider
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
