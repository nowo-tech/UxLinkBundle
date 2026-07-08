<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\DependencyInjection;

use Nowo\UxLinkBundle\Enum\ExternalTargetPolicy;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration tree for nowo_ux_link.
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nowo_ux_link');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('target')->defaultValue('_blank')->end()
                        ->scalarNode('rel')->defaultValue('noopener noreferrer')->end()
                        ->booleanNode('show_icons')->defaultTrue()->end()
                        ->enumNode('external_target_policy')
                            ->values(array_map(static fn (ExternalTargetPolicy $p): string => $p->value, ExternalTargetPolicy::cases()))
                            ->defaultValue(ExternalTargetPolicy::Auto->value)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('families')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->booleanNode('enabled')->defaultTrue()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('providers')
                    ->useAttributeAsKey('family')
                    ->arrayPrototype()
                        ->useAttributeAsKey('name')
                        ->arrayPrototype()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->scalarNode('icon')->defaultNull()->end()
                                ->scalarNode('label')->defaultNull()->end()
                                ->integerNode('priority')->defaultValue(0)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('aliases')
                    ->useAttributeAsKey('family')
                    ->arrayPrototype()
                        ->useAttributeAsKey('alias')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('link')->defaultValue('@NowoUxLinkBundle/components/ux-link.html.twig')->end()
                        ->scalarNode('links')->defaultValue('@NowoUxLinkBundle/components/ux-links.html.twig')->end()
                        ->scalarNode('share_links')->defaultValue('@NowoUxLinkBundle/components/ux-share-links.html.twig')->end()
                        ->scalarNode('download_link')->defaultValue('@NowoUxLinkBundle/components/ux-download-link.html.twig')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
