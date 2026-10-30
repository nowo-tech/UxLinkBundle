<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Model;

/**
 * Options passed to HTML renderers.
 */
final readonly class LinkRenderOptions
{
    /**
     * Creates render options.
     *
     * @param bool        $showIcon  Whether the icon is rendered
     * @param bool        $showLabel Whether the label is rendered
     * @param string|null $template  Twig template overriding the renderer default
     */
    public function __construct(
        public bool $showIcon = true,
        public bool $showLabel = true,
        public ?string $template = null,
    ) {
    }
}
