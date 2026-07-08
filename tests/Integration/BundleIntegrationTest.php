<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Tests\Kernel\TestKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Verifies the bundle boots and the link factory resolves providers.
 */
#[CoversNothing]
final class BundleIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    public function testKernelBootsAndCreatesWhatsappLink(): void
    {
        self::bootKernel();
        /** @var LinkFactoryInterface $factory */
        $factory = self::getContainer()->get(LinkFactoryInterface::class);
        $link = $factory->create('contact', 'whatsapp', [
            'recipient' => '+34600111222',
            'message' => 'Hello',
        ]);

        self::assertStringContainsString('wa.me/34600111222', $link->url);
        self::assertNotEmpty($link->label);
    }

    public function testShareAliasTwitterResolvesToX(): void
    {
        self::bootKernel();
        /** @var LinkFactoryInterface $factory */
        $factory = self::getContainer()->get(LinkFactoryInterface::class);
        $link = $factory->create('share', 'twitter', [
            'url' => 'https://example.com/article',
            'text' => 'Read this',
        ]);

        self::assertSame('x', $link->provider);
        self::assertStringContainsString('twitter.com/intent/tweet', $link->url);
    }
}
