<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model\Options;

/**
 * Family-specific link options.
 */
interface LinkOptionsInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): static;
}
