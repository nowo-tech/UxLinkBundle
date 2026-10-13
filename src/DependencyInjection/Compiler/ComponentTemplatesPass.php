<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\DependencyInjection\Compiler;

use Nowo\UxLinkBundle\Twig\Component\UxDownloadLink;
use Nowo\UxLinkBundle\Twig\Component\UxLinks;
use Nowo\UxLinkBundle\Twig\Component\UxShareLinks;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Applies `templates.links`, `templates.share_links` and `templates.download_link` to the
 * Twig components (overrides the template declared in #[AsTwigComponent]).
 *
 * Must run after autoconfiguration (which adds the `twig.component` tag) and before
 * UX TwigComponent's own pass reads that tag: registered with priority 10 in
 * {@see PassConfig::TYPE_BEFORE_OPTIMIZATION}.
 */
final class ComponentTemplatesPass implements CompilerPassInterface
{
    /** @var array<class-string, string> component service id => template parameter */
    private const COMPONENT_TEMPLATES = [
        UxLinks::class => 'nowo_ux_link.templates.links',
        UxShareLinks::class => 'nowo_ux_link.templates.share_links',
        UxDownloadLink::class => 'nowo_ux_link.templates.download_link',
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::COMPONENT_TEMPLATES as $serviceId => $parameter) {
            if (!$container->hasDefinition($serviceId) || !$container->hasParameter($parameter)) {
                continue;
            }

            $template = $container->getParameterBag()->resolveValue($container->getParameter($parameter));
            if (!\is_string($template) || '' === $template) {
                continue;
            }

            $definition = $container->getDefinition($serviceId);
            $tags = $definition->getTag('twig.component');
            if ([] === $tags) {
                continue;
            }

            $definition->clearTag('twig.component');
            foreach ($tags as $attributes) {
                $attributes['template'] = $template;
                // @igor-ignore - Container compile-time DI mutation; not runtime worker state.
                $definition->addTag('twig.component', $attributes);
            }
        }
    }
}
