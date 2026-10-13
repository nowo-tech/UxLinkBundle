<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\TwigComponent\ComponentFactory;

/**
 * Without templates.* config the components keep the bundle templates.
 */
#[CoversNothing]
final class ComponentTemplatesIntegrationTest extends KernelTestCase
{
    public function testDefaultTemplatesAreTheBundleOnes(): void
    {
        self::bootKernel();

        $factory = self::getContainer()->get('ux.twig_component.component_factory');
        self::assertInstanceOf(ComponentFactory::class, $factory);
        self::assertSame('@NowoUxLinkBundle/components/ux-links.html.twig', $factory->metadataFor('UxLinks')->getTemplate());
        self::assertSame('@NowoUxLinkBundle/components/ux-share-links.html.twig', $factory->metadataFor('UxShareLinks')->getTemplate());
        self::assertSame('@NowoUxLinkBundle/components/ux-download-link.html.twig', $factory->metadataFor('UxDownloadLink')->getTemplate());
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
