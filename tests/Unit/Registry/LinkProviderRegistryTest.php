<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Registry;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\ProviderNotFoundException;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Registry\LinkProviderRegistry
 */
final class LinkProviderRegistryTest extends TestCase
{
    public function testRegistryIndexesProviders(): void
    {
        $config = new BundleConfiguration([], [], ['contact' => ['whatsapp' => ['priority' => 10]]], []);
        $registry = new LinkProviderRegistry([new WhatsappContactProvider()], $config);

        self::assertTrue($registry->has(LinkFamily::Contact, 'whatsapp'));
        self::assertSame('whatsapp', $registry->get(LinkFamily::Contact, 'whatsapp')->getName());
        self::assertCount(1, $registry->all(LinkFamily::Contact));
    }

    public function testMissingProviderThrows(): void
    {
        $registry = new LinkProviderRegistry([], new BundleConfiguration([], [], [], []));

        $this->expectException(ProviderNotFoundException::class);
        $registry->get(LinkFamily::Contact, 'missing');
    }

    public function testHigherPriorityReplacesProvider(): void
    {
        $first = new WhatsappContactProvider();
        $second = new WhatsappContactProvider();
        $registry = new LinkProviderRegistry([], new BundleConfiguration([], [], [], []));
        $registry->add($first, 0);
        $registry->add($second, 5);

        self::assertSame($second, $registry->get(LinkFamily::Contact, 'whatsapp'));
    }
}
