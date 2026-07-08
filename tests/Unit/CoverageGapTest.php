<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\DependencyInjection\Compiler\TwigPathsPass;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Enum\MapAction;
use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Factory\OptionsFactory;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Nowo\UxLinkBundle\Model\LinkRenderOptions;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\DownloadOptions;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use Nowo\UxLinkBundle\Provider\Contact\EmailContactProvider;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use Nowo\UxLinkBundle\Provider\Map\AppleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\GoogleMapsProvider;
use Nowo\UxLinkBundle\Provider\Map\OpenStreetMapProvider;
use Nowo\UxLinkBundle\Provider\Share\EmailShareProvider;
use Nowo\UxLinkBundle\Provider\Share\WhatsappShareProvider;
use Nowo\UxLinkBundle\Provider\Share\XShareProvider;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Renderer\HtmlLinkRenderer;
use Nowo\UxLinkBundle\Renderer\UrlRenderer;
use Nowo\UxLinkBundle\Security\UrlPolicy;
use Nowo\UxLinkBundle\Twig\UxLinkExtension;
use Nowo\UxLinkBundle\Util\PhoneNormalizer;
use Nowo\UxLinkBundle\Util\UrlBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Additional tests targeting uncovered branches.
 */
final class CoverageGapTest extends TestCase
{
    public function testLinkGetUrlWithIconAndIterator(): void
    {
        $link = new Link(LinkFamily::Share, 'x', 'https://example.com', label: 'X', icon: 'icon');
        self::assertSame('https://example.com', $link->getUrl());
        self::assertSame('star', $link->withIcon('star')->icon);

        $collection = (new LinkCollection([$link]))->add(
            new Link(LinkFamily::Contact, 'email', 'mailto:a@b.com'),
        );
        self::assertCount(2, $collection);
        self::assertCount(2, iterator_to_array($collection));
    }

    public function testLinkAttributesMergeClassBranches(): void
    {
        $onlyOther = (new LinkAttributes(class: 'a'))->merge(new LinkAttributes());
        self::assertSame('a', $onlyOther->class);

        $onlyBase = (new LinkAttributes())->merge(new LinkAttributes(class: 'b'));
        self::assertSame('b', $onlyBase->class);
    }

    public function testOptionsFactoryAllFamiliesAndParse(): void
    {
        $factory = new OptionsFactory();
        self::assertSame(LinkFamily::Share, $factory->tryParseFamily('share'));
        self::assertInstanceOf(ContactOptions::class, $factory->create(LinkFamily::Contact, ['email' => 'a@b.com']));
        self::assertInstanceOf(MapOptions::class, $factory->create(LinkFamily::Map, ['latitude' => 1.0, 'longitude' => 2.0]));
        self::assertInstanceOf(DownloadOptions::class, $factory->create(LinkFamily::Download, ['url' => '/f.pdf']));
    }

    public function testUrlPolicyExternalAndSchemes(): void
    {
        self::assertTrue(UrlPolicy::isExternalHttpUrl('https://example.com'));
        self::assertFalse(UrlPolicy::isExternalHttpUrl('mailto:a@b.com'));
        self::assertTrue(UrlPolicy::isAllowedUserUrl('tel:+34600111222'));
        self::assertTrue(UrlPolicy::isAllowedUserUrl('sms:+34600111222'));
        self::assertTrue(UrlPolicy::isAllowedUserUrl('geo:40.4,-3.7'));
    }

    public function testPhoneNormalizerEdgeCases(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        PhoneNormalizer::normalize('123');
    }

    public function testPhoneNormalizerNoDigits(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        PhoneNormalizer::normalize('+++');
    }

    public function testUrlBuilderFragmentAndMailHeader(): void
    {
        self::assertSame('https://example.com#section%20a', UrlBuilder::build('https://example.com', [], 'section a'));
        self::assertSame('https://example.com', UrlBuilder::build('https://example.com'));
        self::assertSame('hello%40world', UrlBuilder::encodeMailHeader('hello@world'));
    }

    public function testHtmlLinkRendererWithCustomOptions(): void
    {
        $twig = new Environment(new ArrayLoader([
            'custom.html.twig' => '<a{% for k,v in attributes %} {{ k }}="{{ v }}"{% endfor %}>{{ link.url }}</a>',
        ]));
        $renderer = new HtmlLinkRenderer($twig);
        $link = new Link(
            LinkFamily::Contact,
            'email',
            'mailto:a@b.com',
            attributes: new LinkAttributes(target: '_blank', class: 'x'),
        );
        $html = $renderer->render($link, new LinkRenderOptions(showIcon: false, template: 'custom.html.twig'));

        self::assertStringContainsString('class="x"', $html);
        self::assertStringContainsString('target="_blank"', $html);
    }

    public function testRegistrySkipsDuplicateWhenPriorityNotHigher(): void
    {
        $first = new WhatsappContactProvider();
        $second = new WhatsappContactProvider();
        $registry = new LinkProviderRegistry([], new BundleConfiguration([], [], [], []));
        $registry->add($first, 5);
        $registry->add($second, 0);

        self::assertSame($first, $registry->get(LinkFamily::Contact, 'whatsapp'));
    }

    public function testEmailContactWithCcAndBcc(): void
    {
        $link = (new EmailContactProvider())->create(new ContactOptions(
            'a@b.com',
            message: 'Body',
            cc: ['c@b.com'],
            bcc: ['b@b.com'],
        ));

        self::assertStringContainsString('cc=c%40b.com', $link->url);
        self::assertStringContainsString('bcc=b%40b.com', $link->url);
    }

    public function testShareProvidersOptionalFields(): void
    {
        $whatsapp = (new WhatsappShareProvider())->create(new ShareOptions('https://example.com', title: 'Title'));
        self::assertStringContainsString('text=', $whatsapp->url);

        $x = (new XShareProvider())->create(new ShareOptions(
            'https://example.com',
            description: 'Desc',
            hashtags: ['#tag', 'other'],
            via: 'nowo',
        ));
        self::assertStringContainsString('hashtags=', $x->url);
        self::assertStringContainsString('via=nowo', $x->url);

        $email = (new EmailShareProvider())->create(new ShareOptions('https://example.com', description: 'Desc'));
        self::assertStringStartsWith('mailto:?', $email->url);
    }

    public function testMapProvidersAdditionalBranches(): void
    {
        $googleDirections = (new GoogleMapsProvider())->create(new MapOptions(
            latitude: 1.0,
            longitude: 2.0,
            origin: 'A',
            destination: 'B',
            action: MapAction::Directions,
        ));
        self::assertStringContainsString('google.com/maps/dir', $googleDirections->url);

        $appleView = (new AppleMapsProvider())->create(MapOptions::fromArray([
            'latitude' => 40.4,
            'longitude' => -3.7,
            'label' => 'Madrid',
        ]));
        self::assertStringContainsString('maps.apple.com', $appleView->url);

        $appleTransit = (new AppleMapsProvider())->create(new MapOptions(
            origin: 'A',
            destination: 'B',
            transportMode: 'transit',
            action: MapAction::Route,
        ));
        self::assertStringContainsString('dirflg=r', $appleTransit->url);

        $appleBike = (new AppleMapsProvider())->create(new MapOptions(
            origin: 'A',
            destination: 'B',
            transportMode: 'bicycling',
            action: MapAction::Route,
        ));
        self::assertStringContainsString('dirflg=c', $appleBike->url);

        $osmView = (new OpenStreetMapProvider())->create(MapOptions::fromArray([
            'latitude' => 1.0,
            'longitude' => 2.0,
            'action' => 'view',
        ]));
        self::assertStringContainsString('mlat=1', $osmView->url);
    }

    public function testShareOptionsHashtagNormalization(): void
    {
        $options = ShareOptions::fromArray([
            'url' => 'https://example.com',
            'hashtags' => ['#one', '', 123, 'two'],
        ]);

        self::assertSame(['one', 'two'], $options->hashtags);
    }

    public function testContactOptionsPhoneRecipient(): void
    {
        $options = ContactOptions::fromArray(['phone' => '+34600111222']);

        self::assertSame('+34600111222', $options->recipient);
    }

    public function testUxLinkExtensionWithAttributes(): void
    {
        $link = new Link(LinkFamily::Contact, 'email', 'mailto:a@b.com');
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($link);
        $renderer = $this->createMock(LinkRendererInterface::class);
        $renderer->method('render')->willReturn('<a class="btn">x</a>');

        $extension = new UxLinkExtension($factory, $renderer, new UrlRenderer());
        $html = $extension->uxLink('contact', 'email', [], ['class' => 'btn']);

        self::assertSame('<a class="btn">x</a>', $html);
    }

    public function testAsLinkProviderAttribute(): void
    {
        $attribute = new AsLinkProvider(priority: 10);

        self::assertSame(10, $attribute->priority);
    }

    public function testLinkAttributesRelInToArray(): void
    {
        $attributes = new LinkAttributes(rel: 'noopener');
        $array = $attributes->toArray();

        self::assertSame('noopener', $array['rel']);
    }

    public function testContactOptionsIgnoresNonArrayCc(): void
    {
        $options = ContactOptions::fromArray([
            'email' => 'a@b.com',
            'cc' => 'invalid',
        ]);

        self::assertSame([], $options->cc);
    }

    public function testShareOptionsValidationBranches(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        ShareOptions::fromArray(['url' => '']);
    }

    public function testShareOptionsIgnoresNonArrayHashtags(): void
    {
        $options = ShareOptions::fromArray([
            'url' => 'https://example.com',
            'hashtags' => 'invalid',
        ]);

        self::assertSame([], $options->hashtags);
    }

    public function testShareOptionsConstructor(): void
    {
        $options = new ShareOptions('https://example.com');

        self::assertSame('https://example.com', $options->url);
    }

    public function testMapProvidersLocationAndCoordsBranches(): void
    {
        $apple = (new AppleMapsProvider())->create(new MapOptions(
            address: 'Office',
            origin: 'Home',
            action: MapAction::Directions,
        ));
        self::assertStringContainsString('maps.apple.com', $apple->url);

        $appleCoords = (new AppleMapsProvider())->create(new MapOptions(
            latitude: 40.4,
            longitude: -3.7,
            origin: 'Home',
            action: MapAction::Directions,
        ));
        self::assertStringContainsString('40.4', $appleCoords->url);

        $google = (new GoogleMapsProvider())->create(new MapOptions(
            latitude: 40.4,
            longitude: -3.7,
            origin: 'Home',
            action: MapAction::Directions,
        ));
        self::assertStringContainsString('40.4', $google->url);

        $googleAddress = (new GoogleMapsProvider())->create(new MapOptions(
            address: 'Madrid',
            action: MapAction::View,
        ));
        self::assertStringContainsString('Madrid', $googleAddress->url);

        $osmSearch = (new OpenStreetMapProvider())->create(MapOptions::fromArray([
            'latitude' => 1.0,
            'longitude' => 2.0,
            'action' => 'search',
        ]));
        self::assertStringContainsString('query=1', $osmSearch->url);
    }

    public function testTwigPathsPassResolvesNestedAlias(): void
    {
        $container = new ContainerBuilder();
        $loaderDef = new Definition();
        $container->setDefinition('twig.loader.native_filesystem', $loaderDef);
        $container->setAlias('twig.loader.alias_a', 'twig.loader.native_filesystem');
        $container->setAlias('twig.loader.alias_b', 'twig.loader.alias_a');
        $container->setAlias('twig.loader.native', 'twig.loader.alias_b');

        (new TwigPathsPass())->process($container);

        self::assertNotEmpty($loaderDef->getMethodCalls());
    }

    public function testAppleMapsDefaultDrivingMode(): void
    {
        $link = (new AppleMapsProvider())->create(new MapOptions(
            origin: 'A',
            destination: 'B',
            action: MapAction::Route,
        ));

        self::assertStringContainsString('dirflg=d', $link->url);
    }
}
