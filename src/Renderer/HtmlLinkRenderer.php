<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Renderer;

use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkRenderOptions;
use Nowo\UxLinkBundle\Security\HtmlAttributePolicy;
use Twig\Environment;

/**
 * Renders links using Twig templates.
 */
final class HtmlLinkRenderer implements LinkRendererInterface
{
    /**
     * Creates the Twig-based renderer.
     *
     * @param Environment $twig            Twig environment
     * @param string      $defaultTemplate Template used when the render options do not set one
     */
    public function __construct(
        private readonly Environment $twig,
        private readonly string $defaultTemplate = '@NowoUxLinkBundle/components/ux-link.html.twig',
    ) {
    }

    /**
     * Renders the link with Twig; only attributes allowed by {@see HtmlAttributePolicy} reach the template.
     *
     * @param Link                   $link          Link to render
     * @param LinkRenderOptions|null $renderOptions Icon/label visibility and template override (defaults when null)
     *
     * @return string Rendered HTML
     */
    public function render(Link $link, ?LinkRenderOptions $renderOptions = null): string
    {
        $renderOptions ??= new LinkRenderOptions();

        $attributes = $link->attributes->safeArray();

        return $this->twig->render(
            $renderOptions->template ?? $this->defaultTemplate,
            [
                'link' => $link,
                'show_icon' => $renderOptions->showIcon,
                'show_label' => $renderOptions->showLabel,
                'attributes' => $attributes,
            ],
        );
    }
}
