<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\DependencyInjection\Compiler;

use Nowo\UxLinkBundle\DependencyInjection\Compiler\ComponentTemplatesPass;
use Nowo\UxLinkBundle\Twig\Component\UxDownloadLink;
use Nowo\UxLinkBundle\Twig\Component\UxLink;
use Nowo\UxLinkBundle\Twig\Component\UxLinks;
use Nowo\UxLinkBundle\Twig\Component\UxShareLinks;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @covers \Nowo\UxLinkBundle\DependencyInjection\Compiler\ComponentTemplatesPass
 */
final class ComponentTemplatesPassTest extends TestCase
{
    public function testConfiguredTemplatesReplaceTheAttributeTemplates(): void
    {
        $container = $this->container();
        $container->setParameter('nowo_ux_link.templates.links', '@App/links.html.twig');
        $container->setParameter('nowo_ux_link.templates.share_links', '%share_tpl%');
        $container->setParameter('share_tpl', '@App/share.html.twig');
        $container->setParameter('nowo_ux_link.templates.download_link', '@App/download.html.twig');

        (new ComponentTemplatesPass())->process($container);

        self::assertSame([['key' => 'UxLinks', 'template' => '@App/links.html.twig']], $container->getDefinition(UxLinks::class)->getTag('twig.component'));
        self::assertSame([['key' => 'UxShareLinks', 'template' => '@App/share.html.twig']], $container->getDefinition(UxShareLinks::class)->getTag('twig.component'));
        self::assertSame([['key' => 'UxDownloadLink', 'template' => '@App/download.html.twig']], $container->getDefinition(UxDownloadLink::class)->getTag('twig.component'));
        // templates.link drives HtmlLinkRenderer, not the UxLink component.
        self::assertSame([['key' => 'UxLink', 'template' => '@NowoUxLinkBundle/components/ux-link-component.html.twig']], $container->getDefinition(UxLink::class)->getTag('twig.component'));
    }

    public function testSkipsMissingDefinitionsParametersEmptyValuesAndUntaggedServices(): void
    {
        $container = new ContainerBuilder();
        $container->setDefinition(UxLinks::class, new Definition(UxLinks::class));
        $container->setParameter('nowo_ux_link.templates.links', '@App/links.html.twig');
        $container->setDefinition(UxShareLinks::class, (new Definition(UxShareLinks::class))->addTag('twig.component', ['key' => 'UxShareLinks', 'template' => 'orig']));
        $container->setParameter('nowo_ux_link.templates.share_links', '');

        (new ComponentTemplatesPass())->process($container);

        self::assertSame([], $container->getDefinition(UxLinks::class)->getTag('twig.component'));
        self::assertSame([['key' => 'UxShareLinks', 'template' => 'orig']], $container->getDefinition(UxShareLinks::class)->getTag('twig.component'));
        self::assertFalse($container->hasDefinition(UxDownloadLink::class));
    }

    private function container(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        foreach ([
            UxLink::class => ['UxLink', '@NowoUxLinkBundle/components/ux-link-component.html.twig'],
            UxLinks::class => ['UxLinks', '@NowoUxLinkBundle/components/ux-links.html.twig'],
            UxShareLinks::class => ['UxShareLinks', '@NowoUxLinkBundle/components/ux-share-links.html.twig'],
            UxDownloadLink::class => ['UxDownloadLink', '@NowoUxLinkBundle/components/ux-download-link.html.twig'],
        ] as $class => [$key, $template]) {
            $container->setDefinition($class, (new Definition($class))->addTag('twig.component', ['key' => $key, 'template' => $template]));
        }

        return $container;
    }
}
