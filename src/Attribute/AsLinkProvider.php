<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Attribute;

/**
 * Autoconfigures a class as a link provider.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsLinkProvider
{
    public function __construct(
        public int $priority = 0,
    ) {
    }
}
