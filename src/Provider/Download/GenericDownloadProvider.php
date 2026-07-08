<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider\Download;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Nowo\UxLinkBundle\Model\Options\DownloadOptions;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Builds enriched download links.
 */
#[AsLinkProvider]
final class GenericDownloadProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily
    {
        return LinkFamily::Download;
    }

    public function getName(): string
    {
        return 'download';
    }

    public function create(LinkOptionsInterface $options): Link
    {
        /** @var DownloadOptions $options */
        $attributes = new LinkAttributes(
            download: $options->filename ?? 'download',
        );

        $metadata = array_filter([
            'filename' => $options->filename,
            'mimeType' => $options->mimeType,
            'size' => $options->size,
            'checksum' => $options->checksum,
            'previewUrl' => $options->previewUrl,
            'description' => $options->description,
        ], static fn (mixed $value): bool => null !== $value && '' !== $value);

        return new Link(
            family: $this->getFamily(),
            provider: $this->getName(),
            url: $options->url,
            label: $options->label,
            attributes: $attributes,
            metadata: $metadata,
        );
    }
}
