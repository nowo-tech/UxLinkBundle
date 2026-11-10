<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Integration;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Provider\Contact\EmailContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\TelephoneContactProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeNegativeEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeZeroEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan\AbstractAttributedProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan\NonAutowirableProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\ProviderScan\ScannedMastodonProvider;
use Nowo\UxLinkBundle\Tests\Kernel\ProviderScenarioKernel;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Which provider wins a built-in family and name, for every way a host provider can be registered.
 */
#[CoversNothing]
final class ProviderPrecedenceIntegrationTest extends KernelTestCase
{
    /**
     * @return iterable<string, array{string, class-string}>
     */
    public static function emailScenarios(): iterable
    {
        // Implementing the interface alone never registers (nor shadows a built-in provider).
        yield 'interface only' => ['interface_only', EmailContactProvider::class];
        // #[AsLinkProvider] with priority <= 0: the built-in provider wins the tie.
        yield 'attribute priority 0' => ['attribute_zero', EmailContactProvider::class];
        yield 'attribute priority -5' => ['attribute_negative', EmailContactProvider::class];
        // #[AsLinkProvider] with priority > 0 replaces the built-in provider.
        yield 'attribute priority 10' => ['attribute_positive', AttributeOnlyEmailProvider::class];
        // Explicit tags behave as in 1.2.1.
        yield 'explicit tag' => ['explicit_tag', InterfaceOnlyEmailProvider::class];
        yield 'explicit tag priority 5' => ['explicit_tag_priority', InterfaceOnlyEmailProvider::class];
        // An explicit tag's priority (0) overrides the attribute's (-5).
        yield 'explicit tag + attribute -5' => ['explicit_tag_overrides_attribute', AttributeNegativeEmailProvider::class];
        // providers.contact.email.priority > 0 makes the application provider replace the built-in one.
        yield 'config priority + explicit tag' => ['config_priority_explicit_tag', InterfaceOnlyEmailProvider::class];
        yield 'config priority + attribute 0' => ['config_priority_attribute_zero', AttributeZeroEmailProvider::class];
        yield 'config priority + attribute 10' => ['config_priority_attribute_positive', AttributeOnlyEmailProvider::class];
        yield 'config priority + attribute 10 + explicit tag' => ['config_priority_attribute_and_tag', AttributeOnlyEmailProvider::class];
    }

    /**
     * @param class-string $expected
     */
    #[DataProvider('emailScenarios')]
    public function testContactEmailWinner(string $scenario, string $expected): void
    {
        self::bootKernel(['environment' => $scenario]);

        /** @var LinkProviderRegistry $registry */
        $registry = self::getContainer()->get(LinkProviderRegistry::class);
        self::assertInstanceOf($expected, $registry->get(LinkFamily::Contact, 'email'));
        self::assertCount(1, array_filter(
            $registry->all(LinkFamily::Contact),
            static fn (object $provider): bool => 'email' === $provider->getName(),
        ));

        /** @var LinkFactoryInterface $factory */
        $factory = self::getContainer()->get(LinkFactoryInterface::class);
        $url = $factory->create('contact', 'email', ['recipient' => 'a@example.com'])->url;
        if (EmailContactProvider::class === $expected) {
            self::assertSame('mailto:a@example.com', $url);
        } else {
            self::assertSame('mailto:host@example.com', $url);
        }
    }

    public function testInterfaceOnlyProviderForAnotherBuiltInNameDoesNotShadowIt(): void
    {
        self::bootKernel(['environment' => 'interface_only']);

        /** @var LinkProviderRegistry $registry */
        $registry = self::getContainer()->get(LinkProviderRegistry::class);
        self::assertInstanceOf(TelephoneContactProvider::class, $registry->get(LinkFamily::Contact, 'telephone'));
    }

    public function testScannedNamespaceSkipsAbstractAndUnusedNonAutowirableImplementers(): void
    {
        // Compiling the container must not fail on NonAutowirableProvider (string $apiKey, never used).
        self::bootKernel(['environment' => 'scan']);

        $container = self::getContainer();
        /** @var LinkProviderRegistry $registry */
        $registry = $container->get(LinkProviderRegistry::class);

        self::assertInstanceOf(ScannedMastodonProvider::class, $registry->get(LinkFamily::Share, 'mastodon'));
        self::assertInstanceOf(TelephoneContactProvider::class, $registry->get(LinkFamily::Contact, 'telephone'));
        self::assertFalse($container->has(NonAutowirableProvider::class));
        self::assertFalse($container->has(AbstractAttributedProvider::class));
        foreach (LinkFamily::cases() as $family) {
            foreach ($registry->all($family) as $provider) {
                self::assertNotInstanceOf(NonAutowirableProvider::class, $provider);
            }
        }
    }

    public function testEveryBuiltInNameResolvesToTheBundleProvider(): void
    {
        self::bootKernel(['environment' => 'attribute_zero']);

        /** @var LinkProviderRegistry $registry */
        $registry = self::getContainer()->get(LinkProviderRegistry::class);
        $builtIn = [
            'contact' => ['email', 'sms', 'telephone', 'whatsapp'],
            'share' => ['email', 'linkedin', 'telegram', 'whatsapp', 'x'],
            'map' => ['apple_maps', 'google_maps', 'openstreetmap', 'waze'],
            'download' => ['download'],
        ];
        foreach ($builtIn as $family => $names) {
            foreach ($names as $name) {
                $provider = $registry->get(LinkFamily::from($family), $name);
                self::assertStringStartsWith('Nowo\\UxLinkBundle\\Provider\\', $provider::class, $family.'/'.$name);
            }
        }
    }

    protected static function getKernelClass(): string
    {
        return ProviderScenarioKernel::class;
    }
}
