<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Contract;

use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkRenderOptions;

/**
 * Renders HTML for a link model.
 */
interface LinkRendererInterface
{
    /**
     * Renders the link as HTML.
     *
     * @param Link                   $link          Link to render
     * @param LinkRenderOptions|null $renderOptions Icon/label visibility and template override (defaults when null)
     *
     * @return string Rendered HTML
     */
    public function render(Link $link, ?LinkRenderOptions $renderOptions = null): string;
}
