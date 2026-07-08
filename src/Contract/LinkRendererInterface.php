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
    public function render(Link $link, ?LinkRenderOptions $renderOptions = null): string;
}
