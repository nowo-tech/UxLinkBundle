<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Twig\Component;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Nowo\UxLinkBundle\Twig\Component\UxDownloadLink;
use Nowo\UxLinkBundle\Twig\Component\UxLink;
use Nowo\UxLinkBundle\Twig\Component\UxLinks;
use Nowo\UxLinkBundle\Twig\Component\UxShareLinks;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Twig\Component\UxLink
 * @covers \Nowo\UxLinkBundle\Twig\Component\UxLinks
 * @covers \Nowo\UxLinkBundle\Twig\Component\UxShareLinks
 * @covers \Nowo\UxLinkBundle\Twig\Component\UxDownloadLink
 */
final class TwigComponentsTest extends TestCase
{
    public function testUxLinkMount(): void
    {
        $link = new Link(LinkFamily::Contact, 'whatsapp', 'https://wa.me/1', label: 'W');
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($link);

        $component = new UxLink($factory);
        $component->mount('contact', 'whatsapp', [], label: 'W', recipient: '+1', class: 'btn');

        self::assertSame('https://wa.me/1', $component->link->url);
        self::assertSame('btn', $component->link->attributes->class);
    }

    public function testUxLinkMountWithUrlAndMessage(): void
    {
        $link = new Link(LinkFamily::Share, 'x', 'https://x.test', label: 'Share');
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($link);

        $component = new UxLink($factory);
        $component->mount('share', 'x', [], label: 'Share', message: 'Hi', url: 'https://x.test');

        self::assertSame('https://x.test', $component->link->url);
    }

    public function testUxLinksMount(): void
    {
        $collection = (new LinkCollection())->add(new Link(LinkFamily::Share, 'x', 'https://x.test'));
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('createMany')->willReturn($collection);

        $component = new UxLinks($factory);
        $component->mount('share', ['x'], ['url' => 'https://x.test']);

        self::assertCount(1, $component->links);
    }

    public function testUxShareLinksMount(): void
    {
        $collection = new LinkCollection();
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('createMany')->willReturn($collection);

        $component = new UxShareLinks($factory);
        $component->mount('https://example.com', 'Title', 'Text', ['linkedin']);

        self::assertSame($collection, $component->links);
    }

    public function testUxDownloadLinkHumanSize(): void
    {
        $link = new Link(LinkFamily::Download, 'download', '/f.pdf', metadata: ['size' => 2048]);
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($link);

        $component = new UxDownloadLink($factory);
        $component->mount('/f.pdf', size: 2048);

        self::assertSame('2 KB', $component->humanSize());

        $factoryWithoutSize = $this->createMock(LinkFactoryInterface::class);
        $factoryWithoutSize->method('create')->willReturn(
            new Link(LinkFamily::Download, 'download', '/f.pdf'),
        );
        $empty = new UxDownloadLink($factoryWithoutSize);
        $empty->mount('/f.pdf');
        self::assertNull($empty->humanSize());
    }
}
