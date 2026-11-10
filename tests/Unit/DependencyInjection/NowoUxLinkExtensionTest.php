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

    public function testLoadAutoconfiguresTheAttributeButNotTheBareInterface(): void
    {
        $container = new ContainerBuilder();
        (new NowoUxLinkExtension())->load([[]], $container);

        self::assertArrayNotHasKey(LinkProviderInterface::class, $container->getAutoconfiguredInstanceof());

        $configurators = $container->getAttributeAutoconfigurators();
        self::assertArrayHasKey(AsLinkProvider::class, $configurators);

        $definition = new ChildDefinition('');
        foreach ($configurators[AsLinkProvider::class] as $configurator) {
            $configurator($definition, new AsLinkProvider(priority: 7));
        }
        self::assertSame([['priority' => 7]], $definition->getTag(NowoUxLinkExtension::PROVIDER_TAG));

        $definition = new ChildDefinition('');
        foreach ($configurators[AsLinkProvider::class] as $configurator) {
            $configurator($definition, new AsLinkProvider());
        }
        self::assertSame([['priority' => -1]], $definition->getTag(NowoUxLinkExtension::PROVIDER_TAG));
    }

    public function testTagPriorityLetsBuiltInProvidersWinTies(): void
    {
        self::assertSame(10, NowoUxLinkExtension::tagPriority(10));
        self::assertSame(1, NowoUxLinkExtension::tagPriority(1));
        self::assertSame(-1, NowoUxLinkExtension::tagPriority(0));
        self::assertSame(-6, NowoUxLinkExtension::tagPriority(-5));
        self::assertSame(\PHP_INT_MIN, NowoUxLinkExtension::tagPriority(\PHP_INT_MIN));
    }

    public function testAlias(): void
    {
        self::assertSame('nowo_ux_link', (new NowoUxLinkExtension())->getAlias());
    }
}
