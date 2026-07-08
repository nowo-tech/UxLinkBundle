<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Twig;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Renderer\UrlRenderer;
use Nowo\UxLinkBundle\Twig\UxLinkExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @covers \Nowo\UxLinkBundle\Twig\UxLinkExtension
 */
final class UxLinkExtensionTest extends TestCase
{
    public function testUxLinkUrlFunction(): void
    {
        $link = new Link(LinkFamily::Contact, 'whatsapp', 'https://wa.me/123');
        $factory = $this->createMock(LinkFactoryInterface::class);
        $factory->method('create')->willReturn($link);
        $renderer = $this->createMock(LinkRendererInterface::class);
        $renderer->method('render')->willReturn('<a href="https://wa.me/123">x</a>');

        $twig = new Environment(new ArrayLoader());
        $twig->addExtension(new UxLinkExtension($factory, $renderer, new UrlRenderer()));

        self::assertSame('https://wa.me/123', $twig->createTemplate('{{ ux_link_url("contact", "whatsapp", {}) }}')->render([]));
    }
}
