<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Factory;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\DownloadOptions;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;

/**
 * Builds typed options DTOs from raw arrays.
 */
final class OptionsFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(LinkFamily $family, array $data): LinkOptionsInterface
    {
        return match ($family) {
            LinkFamily::Contact => ContactOptions::fromArray($data),
            LinkFamily::Share => ShareOptions::fromArray($data),
            LinkFamily::Map => MapOptions::fromArray($data),
            LinkFamily::Download => DownloadOptions::fromArray($data),
        };
    }

    public function tryParseFamily(string $family): LinkFamily
    {
        $parsed = LinkFamily::tryFrom($family);
        if (null === $parsed) {
            throw new InvalidLinkOptionsException(\sprintf('Unknown link family "%s".', $family));
        }

        return $parsed;
    }
}
