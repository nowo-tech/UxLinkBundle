<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Enum;

/**
 * Supported link families.
 */
enum LinkFamily: string
{
    case Contact = 'contact';
    case Share = 'share';
    case Map = 'map';
    case Download = 'download';

    /**
     * @return non-empty-string
     */
    public function translationPrefix(): string
    {
        return $this->value;
    }
}
