<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

/**
 * Host-style contact/telephone provider that only implements the interface (no attribute, no tag).
 */
final class InterfaceOnlyTelephoneProvider extends HostContactProvider
{
    public function getName(): string
    {
        return 'telephone';
    }
}
