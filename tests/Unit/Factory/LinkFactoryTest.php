<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Factory;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Exception\DisabledProviderException;
use Nowo\UxLinkBundle\Factory\LinkFactory;
use Nowo\UxLinkBundle\Factory\OptionsFactory;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Renderer\DefaultIconResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

/**
 * @covers \Nowo\UxLinkBundle\Factory\LinkFactory
 */
final class LinkFactoryTest extends TestCase
{
    public function testCreatesLinkWithTranslatedLabel(): void
    {
        $factory = $this->createFactory();
        $link = $factory->create('contact', 'whatsapp', [
            'recipient' => '+34600111222',
            'message' => 'Hi',
        ]);

        self::assertStringContainsString('wa.me', $link->url);
        self::assertSame('Contact on WhatsApp', $link->label);
        self::assertSame('fa6-brands:whatsapp', $link->icon);
        self::assertSame('_blank', $link->attributes->target);
    }

    public function testDisabledFamilyThrows(): void
    {
        $config = new BundleConfiguration(
            defaults: [],
            families: ['contact' => ['enabled' => false]],
            providers: [],
            aliases: [],
        );
        $factory = $this->createFactory($config);

        $this->expectException(DisabledProviderException::class);
        $factory->create('contact', 'whatsapp', ['recipient' => '+34600111222']);
    }

    private function createFactory(?BundleConfiguration $config = null): LinkFactory
    {
        $config ??= new BundleConfiguration(
            defaults: [
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
                'show_icons' => true,
                'external_target_policy' => 'auto',
            ],
            families: [],
            providers: [],
            aliases: [],
        );

        $registry = new LinkProviderRegistry([new WhatsappContactProvider()], $config);
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['contact.whatsapp.label' => 'Contact on WhatsApp'], 'en', 'NowoUxLinkBundle');

        return new LinkFactory(
            $registry,
            new OptionsFactory(),
            $config,
            new DefaultIconResolver(),
            $translator,
        );
    }
}
