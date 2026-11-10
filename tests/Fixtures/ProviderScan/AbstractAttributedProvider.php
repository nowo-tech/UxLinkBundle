<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

/**
 * Abstract class carrying the attribute: resource loading skips it, so it must never become a provider service.
 */
#[AsLinkProvider(priority: 50)]
abstract class AbstractAttributedProvider extends AbstractLinkProvider
{
}
