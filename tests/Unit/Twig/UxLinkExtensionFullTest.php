<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Twig;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkCollection;
use Nowo\UxLinkBundle\Renderer\UrlRenderer;
use Nowo\UxLinkBundle\Twig\UxLinkExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @covers \Nowo\UxLinkBundle\Twig\UxLinkExtension
 */
final class UxLinkExtensionFullTest extends TestCase
{
    public function testUxLinkAndUxLinks(): void
    {
        $linkA = new Link(LinkFamily::Share, 'x', 'https://a.test');
        $linkB = new Link(LinkFamily::Share, 'linkedin', 'https://b.test');
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($linkA);
        $factory->method('createMany')->willReturn((new LinkCollection())->add($linkA)->add($linkB));

        $renderer = $this->createMock(LinkRendererInterface::class);
        $renderer->method('render')->willReturn('<a href="#">ok</a>');

        $twig = new Environment(new ArrayLoader());
        $twig->addExtension(new UxLinkExtension($factory, $renderer, new UrlRenderer()));

        self::assertStringContainsString('ok', $twig->createTemplate('{{ ux_link("share", "x", {url: "https://a.test"}) }}')->render([]));
        $linksHtml = $twig->createTemplate('{{ ux_links("share", ["x","linkedin"], {url: "https://a.test"}) }}')->render([]);
        self::assertSame(2, substr_count($linksHtml, 'ok'));
    }
}
