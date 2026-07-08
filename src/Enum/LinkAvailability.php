<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Enum;

/**
 * Link availability state for rendering.
 */
enum LinkAvailability: string
{
    case Available = 'available';
    case Disabled = 'disabled';
    case Unavailable = 'unavailable';
}
