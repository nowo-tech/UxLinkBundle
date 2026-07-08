<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Enum;

/**
 * External link target policy.
 */
enum ExternalTargetPolicy: string
{
    case Auto = 'auto';
    case Always = 'always';
    case Never = 'never';
}
