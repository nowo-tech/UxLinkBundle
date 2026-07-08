<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model\Options;

use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Security\UrlPolicy;

/**
 * Share link options.
 */
final readonly class ShareOptions implements LinkOptionsInterface
{
    /**
     * @param list<string> $hashtags
     */
    public function __construct(
        public string $url,
        public ?string $title = null,
        public ?string $text = null,
        public ?string $description = null,
        public array $hashtags = [],
        public ?string $via = null,
        public ?string $media = null,
        public ?string $label = null,
    ) {
        UrlPolicy::assertAllowedUserUrl($url);
    }

    public static function fromArray(array $data): static
    {
        $url = $data['url'] ?? null;
        if (!\is_string($url) || '' === $url) {
            throw new InvalidLinkOptionsException('Share options require a non-empty "url".');
        }

        return new self(
            url: $url,
            title: isset($data['title']) && \is_string($data['title']) ? $data['title'] : null,
            text: isset($data['text']) && \is_string($data['text']) ? $data['text'] : null,
            description: isset($data['description']) && \is_string($data['description']) ? $data['description'] : null,
            hashtags: self::stringList($data['hashtags'] ?? []),
            via: isset($data['via']) && \is_string($data['via']) ? $data['via'] : null,
            media: isset($data['media']) && \is_string($data['media']) ? $data['media'] : null,
            label: isset($data['label']) && \is_string($data['label']) ? $data['label'] : null,
        );
    }

    /**
     * @return list<string>
     */
    private static function stringList(mixed $value): array
    {
        if (!\is_array($value)) {
            return [];
        }

        $result = [];
        foreach ($value as $item) {
            if (\is_string($item) && '' !== $item) {
                $result[] = ltrim($item, '#');
            }
        }

        return $result;
    }
}
