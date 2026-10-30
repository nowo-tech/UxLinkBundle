<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Attribute;

/**
 * Autoconfigures a class as a link provider.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsLinkProvider
{
    /**
     * Marks the class as a link provider service.
     *
     * @param int $priority Registration priority; a provider with priority > 0 replaces an already registered one with the same family and name
     */
    public function __construct(
        public int $priority = 0,
    ) {
    }
}
