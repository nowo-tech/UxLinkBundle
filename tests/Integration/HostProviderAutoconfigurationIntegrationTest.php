<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyProvider;
use Nowo\UxLinkBundle\Tests\Kernel\HostProvidersKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Application providers with only #[AsLinkProvider] (autoconfigure, no explicit tag) are registered; implementing the
 * interface alone does not register a provider.
 */
#[CoversNothing]
final class HostProviderAutoconfigurationIntegrationTest extends KernelTestCase
{
    public function testAttributeOnlyProviderReplacesTheBuiltInOneThroughItsPriority(): void
    {
        self::bootKernel();

        /** @var LinkProviderRegistry $registry */
        $registry = self::getContainer()->get(LinkProviderRegistry::class);
        self::assertInstanceOf(AttributeOnlyEmailProvider::class, $registry->get(LinkFamily::Contact, 'email'));

        /** @var LinkFactoryInterface $factory */
        $factory = self::getContainer()->get(LinkFactoryInterface::class);
        self::assertSame('mailto:host@example.com', $factory->create('contact', 'email', ['recipient' => 'a@example.com'])->url);
    }

    public function testInterfaceOnlyProviderIsNotRegistered(): void
    {
        self::bootKernel();

        /** @var LinkProviderRegistry $registry */
        $registry = self::getContainer()->get(LinkProviderRegistry::class);
        self::assertFalse($registry->has(LinkFamily::Share, 'mastodon'));
        self::assertCount(0, array_filter(
            $registry->all(LinkFamily::Share),
            static fn (object $provider): bool => $provider instanceof InterfaceOnlyProvider,
        ));
    }

    protected static function getKernelClass(): string
    {
        return HostProvidersKernel::class;
    }
}
