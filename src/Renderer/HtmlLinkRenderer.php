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
    public function __construct(
        private readonly Environment $twig,
        private readonly string $defaultTemplate = '@NowoUxLinkBundle/components/ux-link.html.twig',
    ) {
    }

    public function render(Link $link, ?LinkRenderOptions $renderOptions = null): string
    {
        $renderOptions ??= new LinkRenderOptions();

        $attributes = HtmlAttributePolicy::filter($link->attributes->toArray());
        foreach ($attributes as $name => $value) {
            $attributes[$name] = HtmlAttributePolicy::sanitizeValue($value);
        }

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
