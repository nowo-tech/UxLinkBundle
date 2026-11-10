<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\DependencyInjection;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\DependencyInjection\NowoUxLinkExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
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

    public function testLoadAutoconfiguresProviderInterfaceAndAttribute(): void
    {
        $container = new ContainerBuilder();
        (new NowoUxLinkExtension())->load([[]], $container);

        $instanceof = $container->getAutoconfiguredInstanceof();
        self::assertArrayHasKey(LinkProviderInterface::class, $instanceof);
        self::assertSame([[]], $instanceof[LinkProviderInterface::class]->getTag(NowoUxLinkExtension::PROVIDER_TAG));

        $configurators = $container->getAttributeAutoconfigurators();
        self::assertArrayHasKey(AsLinkProvider::class, $configurators);

        $definition = new ChildDefinition('');
        foreach ($configurators[AsLinkProvider::class] as $configurator) {
            $configurator($definition, new AsLinkProvider(priority: 7));
        }
        self::assertSame([['priority' => 7]], $definition->getTag(NowoUxLinkExtension::PROVIDER_TAG));
    }

    public function testAlias(): void
    {
        self::assertSame('nowo_ux_link', (new NowoUxLinkExtension())->getAlias());
    }
}
