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
 * Builds Telegram share links.
 */
#[AsLinkProvider]
final class TelegramShareProvider extends AbstractLinkProvider
{
    /**
     * Returns the share family.
     */
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Share;
    }

    /**
     * Returns the provider name (`telegram`).
     */
    public function getName(): string
    {
        return 'telegram';
    }

    /**
     * Builds Telegram share links from the share options.
     *
     * @param LinkOptionsInterface $options ShareOptions instance
     */
    public function create(LinkOptionsInterface $options): Link
    {
        /** @var ShareOptions $options */
        $url = UrlBuilder::build(
            'https://t.me/share/url',
            [
                'url' => $options->url,
                'text' => $options->text ?? $options->title,
            ],
        );

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $url,
            label: $options->label,
        );
    }
}
