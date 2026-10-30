<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Contract;

use Nowo\UxLinkBundle\Model\Link;

/**
 * Resolves a logical icon name for a link.
 */
interface IconResolverInterface
{
    /**
     * Returns the logical icon name for the link (e.g. `fa6-brands:whatsapp`), or null when there is none.
     *
     * @param Link $link Link to resolve the icon for
     */
    public function resolve(Link $link): ?string;
}
