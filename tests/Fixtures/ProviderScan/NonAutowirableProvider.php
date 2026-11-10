<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Implements the interface with a constructor that cannot be autowired and is never used: it must not break the
 * container compilation (it is not registered as a provider without the attribute or a tag).
 */
final class NonAutowirableProvider extends AbstractLinkProvider
{
    public function __construct(private readonly string $apiKey)
    {
    }

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
        return new Link(LinkFamily::Contact, 'telephone', 'tel:'.$this->apiKey, label: 'Never');
    }
}
