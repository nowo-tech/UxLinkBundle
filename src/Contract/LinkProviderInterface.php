<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Contract;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;

/**
 * Builds links for a specific family and provider.
 */
interface LinkProviderInterface
{
    public function getFamily(): LinkFamily;

    /**
     * @return non-empty-lowercase-string
     */
    public function getName(): string;

    public function supports(LinkFamily $family, string $provider): bool;

    public function create(LinkOptionsInterface $options): Link;
}
