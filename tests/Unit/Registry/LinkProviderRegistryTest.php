<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Registry;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\ProviderNotFoundException;
use Nowo\UxLinkBundle\Provider\Contact\EmailContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeZeroEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyEmailProvider;
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

    public function testConfigPriorityLetsAnApplicationProviderReplaceTheBuiltInOne(): void
    {
        $builtIn = new EmailContactProvider();
        $host = new InterfaceOnlyEmailProvider();
        $config = new BundleConfiguration([], [], ['contact' => ['email' => ['priority' => 5]]], []);

        // The built-in provider usually comes last (same tag priority, defined after the application services), but
        // the result must not depend on the order.
        self::assertSame($host, (new LinkProviderRegistry([$host, $builtIn], $config))->get(LinkFamily::Contact, 'email'));
        self::assertSame($host, (new LinkProviderRegistry([$builtIn, $host], $config))->get(LinkFamily::Contact, 'email'));
    }

    public function testConfigPriorityKeepsTheFirstOfSeveralApplicationProviders(): void
    {
        $first = new InterfaceOnlyEmailProvider();
        $second = new AttributeZeroEmailProvider();
        $config = new BundleConfiguration([], [], ['contact' => ['email' => ['priority' => 5]]], []);
        $registry = new LinkProviderRegistry([$first, $second, new EmailContactProvider()], $config);

        self::assertSame($first, $registry->get(LinkFamily::Contact, 'email'));
    }

    public function testConfigPriorityNeverLetsABuiltInProviderReplaceAnother(): void
    {
        $first = new WhatsappContactProvider();
        $second = new WhatsappContactProvider();
        $config = new BundleConfiguration([], [], ['contact' => ['whatsapp' => ['priority' => 5]]], []);
        $registry = new LinkProviderRegistry([$first, $second], $config);

        self::assertSame($first, $registry->get(LinkFamily::Contact, 'whatsapp'));
    }

    public function testWithoutConfigPriorityTheFirstProviderWins(): void
    {
        $builtIn = new EmailContactProvider();
        $host = new InterfaceOnlyEmailProvider();
        $empty = new BundleConfiguration([], [], [], []);

        self::assertSame($builtIn, (new LinkProviderRegistry([$builtIn, $host], $empty))->get(LinkFamily::Contact, 'email'));
        self::assertSame($host, (new LinkProviderRegistry([$host, $builtIn], $empty))->get(LinkFamily::Contact, 'email'));
    }

    public function testSkipsDuplicateWhenPriorityNotHigher(): void
    {
        $first = new WhatsappContactProvider();
        $second = new WhatsappContactProvider();
        $registry = new LinkProviderRegistry([$first, $second], new BundleConfiguration([], [], [], []));

        self::assertSame($first, $registry->get(LinkFamily::Contact, 'whatsapp'));
    }
}
