<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

/**
 * Renders bundle Twig templates through the container.
 */
#[CoversNothing]
final class TwigRenderingIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testUxLinkTemplateRendersEscapedLabel(): void
    {
        self::bootKernel();
        /** @var Environment $twig */
        $twig = self::getContainer()->get('twig');

        $html = $twig->render('@NowoUxLinkBundle/components/ux-link.html.twig', [
            'link' => new \Nowo\UxLinkBundle\Model\Link(
                \Nowo\UxLinkBundle\Enum\LinkFamily::Contact,
                'email',
                'mailto:a@b.com',
                label: 'Send <script>',
            ),
            'attributes' => ['class' => 'link'],
            'show_icon' => false,
            'show_label' => true,
        ]);

        self::assertStringContainsString('mailto:a@b.com', $html);
        self::assertStringNotContainsString('<script>', $html);
    }

    public function testTwigExtensionFunctionsAreRegistered(): void
    {
        self::bootKernel();
        /** @var Environment $twig */
        $twig = self::getContainer()->get('twig');

        self::assertNotNull($twig->getFunction('ux_link_url'));
        self::assertNotNull($twig->getFunction('ux_link'));
        self::assertNotNull($twig->getFunction('ux_links'));
    }
}
