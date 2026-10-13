<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Tests\Kernel\CustomTemplatesKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\TwigComponent\ComponentFactory;

/**
 * templates.links / share_links / download_link reach the Twig components (compiler pass ordering).
 */
#[CoversNothing]
final class CustomComponentTemplatesIntegrationTest extends KernelTestCase
{
    public function testConfiguredTemplatesOverrideTheComponentTemplates(): void
    {
        self::bootKernel();

        $factory = self::getContainer()->get('ux.twig_component.component_factory');
        self::assertInstanceOf(ComponentFactory::class, $factory);
        self::assertSame('custom/ux-links.html.twig', $factory->metadataFor('UxLinks')->getTemplate());
        self::assertSame('custom/ux-share-links.html.twig', $factory->metadataFor('UxShareLinks')->getTemplate());
        self::assertSame('custom/ux-download-link.html.twig', $factory->metadataFor('UxDownloadLink')->getTemplate());
        self::assertSame('@NowoUxLinkBundle/components/ux-link-component.html.twig', $factory->metadataFor('UxLink')->getTemplate());
    }

    protected static function getKernelClass(): string
    {
        return CustomTemplatesKernel::class;
    }
}
