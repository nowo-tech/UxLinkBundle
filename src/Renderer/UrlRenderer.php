<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Renderer;

use Nowo\UxLinkBundle\Model\Link;

/**
 * Returns the URL string for a link model.
 */
final class UrlRenderer
{
    public function render(Link $link): string
    {
        return $link->getUrl();
    }
}
