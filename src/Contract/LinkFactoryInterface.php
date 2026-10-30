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
     * Creates one link for a family and provider, applying the bundle defaults (label, icon, target/rel).
     *
     * @param string               $family   Family value (`contact`, `share`, `map`, `download`)
     * @param string               $provider Provider name or configured alias (case-insensitive)
     * @param array<string, mixed> $options
     */
    public function create(string $family, string $provider, array $options = []): Link;

    /**
     * Creates one link per provider of a family with the same options.
     *
     * @param string               $family    Family value (`contact`, `share`, `map`, `download`)
     * @param list<string>         $providers
     * @param array<string, mixed> $options
     */
    public function createMany(string $family, array $providers, array $options = []): LinkCollection;
}
