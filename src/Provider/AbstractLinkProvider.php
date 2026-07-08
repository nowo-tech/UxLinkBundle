<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Provider;

use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;

/**
 * Base provider with default supports() logic.
 */
abstract class AbstractLinkProvider implements LinkProviderInterface
{
    public function supports(LinkFamily $family, string $provider): bool
    {
        return $family === $this->getFamily() && $provider === $this->getName();
    }
}
