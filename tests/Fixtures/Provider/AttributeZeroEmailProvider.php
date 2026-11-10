<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;

/**
 * Host-style contact/email provider with #[AsLinkProvider] and the default priority 0.
 */
#[AsLinkProvider]
final class AttributeZeroEmailProvider extends HostContactProvider
{
}
