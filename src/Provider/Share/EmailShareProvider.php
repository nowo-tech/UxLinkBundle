<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Share;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Builds mailto: share links.
 */
#[AsLinkProvider]
final class EmailShareProvider extends AbstractLinkProvider
{
    /**
     * Returns the share family.
     */
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Share;
    }

    /**
     * Returns the provider name (`email`).
     */
    public function getName(): string
    {
        return 'email';
    }

    /**
     * Builds mailto: share links from the share options.
     *
     * @param LinkOptionsInterface $options ShareOptions instance
     */
    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ShareOptions $options */
        $subject = $options->title ?? '';
        $body = $options->text ?? $options->description ?? '';
        if ('' !== $body) {
            $body .= "\n\n";
        }
        $body .= $options->url;

        $query = http_build_query(
            array_filter(['subject' => $subject, 'body' => $body]),
            '',
            '&',
            \PHP_QUERY_RFC3986,
        );

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: 'mailto:?'.$query,
            label: $options->label,
        );
    }
}
