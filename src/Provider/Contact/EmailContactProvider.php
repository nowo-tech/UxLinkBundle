<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Contact;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Builds mailto: contact links.
 */
#[AsLinkProvider]
final class EmailContactProvider extends AbstractLinkProvider
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
        /** @var ContactOptions $options */
        $query = [];
        if (null !== $options->subject && '' !== $options->subject) {
            $query['subject'] = $options->subject;
        }
        if (null !== $options->message && '' !== $options->message) {
            $query['body'] = $options->message;
        }
        if ([] !== $options->cc) {
            $query['cc'] = implode(',', $options->cc);
        }
        if ([] !== $options->bcc) {
            $query['bcc'] = implode(',', $options->bcc);
        }

        $url = 'mailto:'.$options->recipient;
        if ([] !== $query) {
            $url .= '?'.http_build_query($query, '', '&', \PHP_QUERY_RFC3986);
        }

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
