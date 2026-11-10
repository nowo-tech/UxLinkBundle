<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Attribute;

use Nowo\UxLinkBundle\Contract\LinkProviderInterface;

/**
 * Autoconfigures a class as a link provider (services with `autoconfigure: true`).
 *
 * Implementing {@see LinkProviderInterface} alone does not register a service; use this
 * attribute or the `nowo_ux_link.provider` tag. When the service also has an explicit `nowo_ux_link.provider` tag,
 * Symfony uses the explicit tag's priority and ignores this one.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsLinkProvider
{
    /**
     * Marks the class as a link provider service.
     *
     * @param int $priority Registration priority. Providers with a higher priority are registered first, and the first
     *                      provider registered for a family and name wins. The built-in providers have priority 0 and win
     *                      ties, so only a priority > 0 replaces a built-in provider; 0 (the default) or less only adds new
     *                      names (or loses to an existing provider with the same family and name)
     */
    public function __construct(
        public int $priority = 0,
    ) {
    }
}
