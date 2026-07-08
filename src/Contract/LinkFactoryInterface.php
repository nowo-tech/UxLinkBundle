<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Contract;

use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkCollection;

/**
 * Creates normalized link models from family, provider, and options.
 */
interface LinkFactoryInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function create(string $family, string $provider, array $options = []): Link;

    /**
     * @param list<string>         $providers
     * @param array<string, mixed> $options
     */
    public function createMany(string $family, array $providers, array $options = []): LinkCollection;
}
