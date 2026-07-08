<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

/**
 * Options passed to HTML renderers.
 */
final readonly class LinkRenderOptions
{
    public function __construct(
        public bool $showIcon = true,
        public bool $showLabel = true,
        public ?string $template = null,
    ) {
    }
}
