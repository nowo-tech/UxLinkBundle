<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Contract;

use Nowo\UxLinkBundle\Model\Link;

/**
 * Resolves a logical icon name for a link.
 */
interface IconResolverInterface
{
    public function resolve(Link $link): ?string;
}
