<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Enum;

/**
 * Map link intent.
 */
enum MapAction: string
{
    case View = 'view';
    case Search = 'search';
    case Directions = 'directions';
    case Route = 'route';
}
