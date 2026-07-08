<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Fixtures\app;

use Nowo\UxLinkBundle\NowoUxLinkBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\UX\TwigComponent\TwigComponentBundle;

/**
 * Registers bundles for the test kernel (mirrors bundles.php for static analysis).
 */
final class BundleRegistry
{
    /**
     * @return list<class-string<BundleInterface>>
     */
    public static function all(): array
    {
        return [
            FrameworkBundle::class,
            TwigBundle::class,
            TwigComponentBundle::class,
            NowoUxLinkBundle::class,
        ];
    }
}
