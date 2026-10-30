<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Share;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;
use Nowo\UxLinkBundle\Util\UrlBuilder;

/**
 * Builds X (Twitter) intent links.
 */
#[AsLinkProvider]
final class XShareProvider extends AbstractLinkProvider
{
    /**
     * Returns the share family.
     */
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Share;
    }

    /**
     * Returns the provider name (`x`).
     */
    public function getName(): string
    {
        return 'x';
    }

    /**
     * Builds X (Twitter) intent links from the share options.
     *
     * @param LinkOptionsInterface $options ShareOptions instance
     */
    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ShareOptions $options */
        $text = $options->text ?? $options->title ?? $options->description;
        $query = [
            'url' => $options->url,
            'text' => $text,
            'via' => $options->via,
        ];
        if ([] !== $options->hashtags) {
            $query['hashtags'] = implode(',', $options->hashtags);
        }

        $url = UrlBuilder::build('https://twitter.com/intent/tweet', $query);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
