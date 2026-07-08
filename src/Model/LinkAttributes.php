<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

/**
 * Safe HTML attributes for rendered links.
 */
final readonly class LinkAttributes
{
    /**
     * @param array<string, string> $extra
     */
    public function __construct(
        public ?string $target = null,
        public ?string $rel = null,
        public ?string $class = null,
        public ?string $ariaLabel = null,
        public ?string $download = null,
        public array $extra = [],
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $extra = [];
        foreach ($data as $key => $value) {
            if (!\is_string($value)) {
                continue;
            }
            $extra[$key] = $value;
        }

        return new self(
            target: isset($data['target']) && \is_string($data['target']) ? $data['target'] : null,
            rel: isset($data['rel']) && \is_string($data['rel']) ? $data['rel'] : null,
            class: isset($data['class']) && \is_string($data['class']) ? $data['class'] : null,
            ariaLabel: isset($data['aria-label']) && \is_string($data['aria-label']) ? $data['aria-label'] : null,
            download: isset($data['download']) && \is_string($data['download']) ? $data['download'] : null,
            extra: $extra,
        );
    }

    public function merge(self $other): self
    {
        return new self(
            target: $other->target ?? $this->target,
            rel: $other->rel ?? $this->rel,
            class: $this->mergeClass($other->class),
            ariaLabel: $other->ariaLabel ?? $this->ariaLabel,
            download: $other->download ?? $this->download,
            extra: [...$this->extra, ...$other->extra],
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $attributes = $this->extra;

        if (null !== $this->target) {
            $attributes['target'] = $this->target;
        }
        if (null !== $this->rel) {
            $attributes['rel'] = $this->rel;
        }
        if (null !== $this->class) {
            $attributes['class'] = $this->class;
        }
        if (null !== $this->ariaLabel) {
            $attributes['aria-label'] = $this->ariaLabel;
        }
        if (null !== $this->download) {
            $attributes['download'] = $this->download;
        }

        return $attributes;
    }

    private function mergeClass(?string $otherClass): ?string
    {
        if (null === $otherClass) {
            return $this->class;
        }
        if (null === $this->class) {
            return $otherClass;
        }

        return trim($this->class.' '.$otherClass);
    }
}
