<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Twig;

use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Nowo\UxLinkBundle\Renderer\UrlRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig functions for UX link generation.
 */
final class UxLinkExtension extends AbstractExtension
{
    public function __construct(
        private readonly LinkFactoryInterface $linkFactory,
        private readonly LinkRendererInterface $linkRenderer,
        private readonly UrlRenderer $urlRenderer,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ux_link_url', $this->uxLinkUrl(...)),
            new TwigFunction('ux_link', $this->uxLink(...), ['is_safe' => ['html']]),
            new TwigFunction('ux_links', $this->uxLinks(...), ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array<string, mixed> $options
     */
    public function uxLinkUrl(string $family, string $provider, array $options = []): string
    {
        return $this->urlRenderer->render($this->linkFactory->create($family, $provider, $options));
    }

    /**
     * @param array<string, mixed> $options
     * @param array<string, mixed> $attributes
     */
    public function uxLink(string $family, string $provider, array $options = [], array $attributes = []): string
    {
        $link = $this->linkFactory->create($family, $provider, $options);
        if ([] !== $attributes) {
            $link = $link->withAttributes(LinkAttributes::fromArray($attributes));
        }

        return $this->linkRenderer->render($link);
    }

    /**
     * @param list<string>         $providers
     * @param array<string, mixed> $options
     */
    public function uxLinks(string $family, array $providers, array $options = []): string
    {
        $collection = $this->linkFactory->createMany($family, $providers, $options);
        $html = '';
        foreach ($collection as $link) {
            $html .= $this->linkRenderer->render($link);
        }

        return $html;
    }
}
