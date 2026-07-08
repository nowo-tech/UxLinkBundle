<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model\Options;

use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;

/**
 * Contact link options.
 */
final readonly class ContactOptions implements LinkOptionsInterface
{
    /**
     * @param list<string> $cc
     * @param list<string> $bcc
     */
    public function __construct(
        public string $recipient,
        public ?string $message = null,
        public ?string $subject = null,
        public array $cc = [],
        public array $bcc = [],
        public ?string $label = null,
    ) {
    }

    public static function fromArray(array $data): static
    {
        $recipient = $data['recipient'] ?? $data['email'] ?? $data['phone'] ?? null;
        if (!\is_string($recipient) || '' === $recipient) {
            throw new InvalidLinkOptionsException('Contact options require a non-empty "recipient".');
        }

        return new self(
            recipient: $recipient,
            message: isset($data['message']) && \is_string($data['message']) ? $data['message'] : null,
            subject: isset($data['subject']) && \is_string($data['subject']) ? $data['subject'] : null,
            cc: self::stringList($data['cc'] ?? []),
            bcc: self::stringList($data['bcc'] ?? []),
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
                $result[] = $item;
            }
        }

        return $result;
    }
}
