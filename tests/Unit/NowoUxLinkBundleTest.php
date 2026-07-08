<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit;

use Nowo\UxLinkBundle\NowoUxLinkBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Nowo\UxLinkBundle\NowoUxLinkBundle
 */
final class NowoUxLinkBundleTest extends TestCase
{
    public function testBuildRegistersCompilerPass(): void
    {
        $container = new ContainerBuilder();
        (new NowoUxLinkBundle())->build($container);

        self::assertNotEmpty($container->getCompilerPassConfig()->getBeforeOptimizationPasses());
    }
}
