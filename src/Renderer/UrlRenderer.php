<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Renderer;

use Nowo\UxLinkBundle\Model\Link;

/**
 * Returns the URL string for a link model.
 */
final class UrlRenderer
{
    /**
     * Returns the URL of the link.
     *
     * @param Link $link Link to render
     */
    public function render(Link $link): string
    {
        return $link->getUrl();
    }
}
