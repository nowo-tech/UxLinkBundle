<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\DependencyInjection;

use Nowo\UxLinkBundle\DependencyInjection\NowoUxLinkExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Nowo\UxLinkBundle\DependencyInjection\NowoUxLinkExtension
 */
final class NowoUxLinkExtensionTest extends TestCase
{
    public function testLoadRegistersParametersAndServices(): void
    {
        $container = new ContainerBuilder();
        (new NowoUxLinkExtension())->load([['aliases' => ['share' => ['twitter' => 'x']]]], $container);

        self::assertTrue($container->hasParameter('nowo_ux_link.config'));
        self::assertTrue($container->hasParameter('nowo_ux_link.templates.link'));
        self::assertTrue($container->hasDefinition('Nowo\\UxLinkBundle\\Config\\BundleConfiguration'));
        self::assertTrue($container->hasDefinition('Nowo\\UxLinkBundle\\Factory\\LinkFactory'));
    }

    public function testAlias(): void
    {
        self::assertSame('nowo_ux_link', (new NowoUxLinkExtension())->getAlias());
    }
}
