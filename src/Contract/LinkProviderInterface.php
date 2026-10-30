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
    /**
     * Returns the link family this provider builds links for.
     */
    public function getFamily(): LinkFamily;

    /**
     * Returns the provider name used in configuration, Twig functions and components (e.g. `linkedin`).
     *
     * @return non-empty-lowercase-string
     */
    public function getName(): string;

    /**
     * Whether this provider handles the given family and provider name.
     *
     * @param LinkFamily $family   Requested family
     * @param string     $provider Requested provider name
     */
    public function supports(LinkFamily $family, string $provider): bool;

    /**
     * Builds the link URL and model from the family-specific options.
     *
     * @param LinkOptionsInterface $options Typed options of the provider family
     */
    public function create(LinkOptionsInterface $options): Link;
}
