<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\Provider;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;

/**
 * Host-style contact/email provider with a negative #[AsLinkProvider] priority.
 */
#[AsLinkProvider(priority: -5)]
final class AttributeNegativeEmailProvider extends HostContactProvider
{
}
