<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Twig\Component;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Model\Link;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Renders an enriched download link.
 */
#[AsTwigComponent(
    name: 'UxDownloadLink',
    template: '@NowoUxLinkBundle/components/ux-download-link.html.twig',
)]
final class UxDownloadLink
{
    public Link $link;

    /**
     * Creates the component.
     *
     * @param LinkFactoryInterface $linkFactory Factory building the download link
     */
    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
    ) {
    }

    /**
     * Builds the download link from the component props (empty props are dropped).
     *
     * @param string          $url         File URL
     * @param string|null     $filename    Suggested file name
     * @param string|null     $mimeType    MIME type shown to the user
     * @param int|string|null $size        Size in bytes (int or digit string)
     * @param string|null     $label       Link label
     * @param string|null     $checksum    Checksum shown to the user
     * @param string|null     $previewUrl  Optional preview URL
     * @param string|null     $description Optional description
     */
    public function mount(
        string $url,
        ?string $filename = null,
        ?string $mimeType = null,
        int|string|null $size = null,
        ?string $label = null,
        ?string $checksum = null,
        ?string $previewUrl = null,
        ?string $description = null,
    ): void {
        // @igor-ignore - Not shared worker service state.
        $this->link = $this->linkFactory->create('download', 'download', array_filter([
            'url' => $url,
            'filename' => $filename,
            'mimeType' => $mimeType,
            'size' => $size,
            'label' => $label,
            'checksum' => $checksum,
            'previewUrl' => $previewUrl,
            'description' => $description,
        ]));
    }

    /**
     * Returns the file size in human-readable units (B, KB, MB, GB), or null when the size is unknown.
     */
    public function humanSize(): ?string
    {
        $size = $this->link->metadata['size'] ?? null;
        if (!\is_int($size) || $size < 0) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $size > 0 ? (int) floor(log($size, 1024)) : 0;
        $power = min($power, \count($units) - 1);

        return round($size / (1024 ** $power), 1).' '.$units[$power];
    }
}
