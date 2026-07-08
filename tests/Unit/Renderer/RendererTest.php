<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Renderer;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Renderer\DefaultIconResolver;
use Nowo\UxLinkBundle\Renderer\HtmlLinkRenderer;
use Nowo\UxLinkBundle\Renderer\UrlRenderer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @covers \Nowo\UxLinkBundle\Renderer\DefaultIconResolver
 * @covers \Nowo\UxLinkBundle\Renderer\HtmlLinkRenderer
 * @covers \Nowo\UxLinkBundle\Renderer\UrlRenderer
 */
final class RendererTest extends TestCase
{
    public function testUrlRenderer(): void
    {
        $link = new Link(LinkFamily::Contact, 'email', 'mailto:a@b.com');
        self::assertSame('mailto:a@b.com', (new UrlRenderer())->render($link));
    }

    public function testDefaultIconResolver(): void
    {
        $link = new Link(LinkFamily::Share, 'linkedin', 'https://example.com');
        self::assertSame('fa6-brands:linkedin', (new DefaultIconResolver())->resolve($link));
    }

    public function testHtmlLinkRenderer(): void
    {
        $twig = new Environment(new ArrayLoader([
            'link.html.twig' => '<a href="{{ link.url }}">{{ link.label }}</a>',
        ]));
        $renderer = new HtmlLinkRenderer($twig, 'link.html.twig');
        $html = $renderer->render(new Link(LinkFamily::Contact, 'email', 'mailto:a@b.com', label: 'Email'));

        self::assertStringContainsString('mailto:a@b.com', $html);
        self::assertStringContainsString('Email', $html);
    }
}
