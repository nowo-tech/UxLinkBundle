<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model\Options;

use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Exception\InvalidUrlException;
use Nowo\UxLinkBundle\Security\UrlPolicy;

/**
 * Download link options.
 */
final readonly class DownloadOptions implements LinkOptionsInterface
{
    /**
     * Creates download options; the URL and the preview URL must pass {@see UrlPolicy}.
     *
     * @param string      $url         File URL
     * @param string|null $filename    Suggested file name (`download` attribute)
     * @param string|null $mimeType    MIME type shown to the user
     * @param int|null    $size        Size in bytes
     * @param string|null $checksum    Checksum shown to the user
     * @param string|null $label       Link label
     * @param string|null $previewUrl  Optional preview URL
     * @param string|null $description Optional description
     *
     * @throws InvalidUrlException When a URL uses a forbidden scheme
     */
    public function __construct(
        public string $url,
        public ?string $filename = null,
        public ?string $mimeType = null,
        public ?int $size = null,
        public ?string $checksum = null,
        public ?string $label = null,
        public ?string $previewUrl = null,
        public ?string $description = null,
    ) {
        UrlPolicy::assertAllowedUserUrl($url);
        if (null !== $previewUrl) {
            UrlPolicy::assertAllowedUserUrl($previewUrl);
        }
    }

    /**
     * Builds download options from a raw array (`size` accepts an int or a digit string).
     *
     * @throws InvalidLinkOptionsException When no non-empty `url` is given
     */
    public static function fromArray(array $data): static
    {
        $url = $data['url'] ?? null;
        if (!\is_string($url) || '' === $url) {
            throw new InvalidLinkOptionsException('Download options require a non-empty "url".');
        }

        $size = $data['size'] ?? null;
        $sizeInt = \is_int($size) ? $size : (\is_string($size) && ctype_digit($size) ? (int) $size : null);

        return new self(
            url: $url,
            filename: isset($data['filename']) && \is_string($data['filename']) ? $data['filename'] : null,
            mimeType: isset($data['mimeType']) && \is_string($data['mimeType']) ? $data['mimeType'] : null,
            size: $sizeInt,
            checksum: isset($data['checksum']) && \is_string($data['checksum']) ? $data['checksum'] : null,
            label: isset($data['label']) && \is_string($data['label']) ? $data['label'] : null,
            previewUrl: isset($data['previewUrl']) && \is_string($data['previewUrl']) ? $data['previewUrl'] : null,
            description: isset($data['description']) && \is_string($data['description']) ? $data['description'] : null,
        );
    }
}
