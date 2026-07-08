<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Factory;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Enum\ExternalTargetPolicy;
use Nowo\UxLinkBundle\Exception\DisabledProviderException;
use Nowo\UxLinkBundle\Factory\LinkFactory;
use Nowo\UxLinkBundle\Factory\OptionsFactory;
use Nowo\UxLinkBundle\Provider\Contact\EmailContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\TelephoneContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Renderer\DefaultIconResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

/**
 * @covers \Nowo\UxLinkBundle\Factory\LinkFactory
 */
final class LinkFactoryExtendedTest extends TestCase
{
    public function testCreateMany(): void
    {
        $factory = $this->factory();
        $collection = $factory->createMany('contact', ['whatsapp', 'telephone'], ['recipient' => '+34600111222']);

        self::assertCount(2, $collection);
    }

    public function testDisabledProviderThrows(): void
    {
        $config = new BundleConfiguration([], [], ['contact' => ['whatsapp' => ['enabled' => false]]], []);
        $factory = $this->factory($config);

        $this->expectException(DisabledProviderException::class);
        $factory->create('contact', 'whatsapp', ['recipient' => '+34600111222']);
    }

    public function testMailtoLinkHasNoExternalTargetWhenPolicyNever(): void
    {
        $config = new BundleConfiguration(
            defaults: ['external_target_policy' => ExternalTargetPolicy::Never->value],
            families: [],
            providers: [],
            aliases: [],
        );
        $registry = new LinkProviderRegistry([new EmailContactProvider()], $config);
        $factory = new LinkFactory($registry, new OptionsFactory(), $config, new DefaultIconResolver(), $this->translator());

        $link = $factory->create('contact', 'email', ['recipient' => 'a@b.com']);

        self::assertNull($link->attributes->target);
    }

    public function testAlwaysPolicyAddsTargetToTelLink(): void
    {
        $config = new BundleConfiguration(
            defaults: ['external_target_policy' => ExternalTargetPolicy::Always->value, 'target' => '_blank'],
            families: [],
            providers: [],
            aliases: [],
        );
        $registry = new LinkProviderRegistry([new TelephoneContactProvider()], $config);
        $factory = new LinkFactory($registry, new OptionsFactory(), $config, new DefaultIconResolver(), $this->translator());

        $link = $factory->create('contact', 'telephone', ['recipient' => '+34600111222']);

        self::assertSame('_blank', $link->attributes->target);
    }

    public function testCustomLabelFromConfig(): void
    {
        $config = new BundleConfiguration(
            defaults: [],
            families: [],
            providers: ['contact' => ['whatsapp' => ['label' => 'Custom']]],
            aliases: [],
        );
        $factory = $this->factory($config);
        $link = $factory->create('contact', 'whatsapp', ['recipient' => '+34600111222']);

        self::assertSame('Custom', $link->label);
    }

    private function factory(?BundleConfiguration $config = null): LinkFactory
    {
        $config ??= new BundleConfiguration([], [], [], []);
        $registry = new LinkProviderRegistry([new WhatsappContactProvider(), new TelephoneContactProvider()], $config);

        return new LinkFactory($registry, new OptionsFactory(), $config, new DefaultIconResolver(), $this->translator());
    }

    private function translator(): Translator
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());

        return $translator;
    }
}
