<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\DependencyInjection;

use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Loads bundle services and configuration.
 */
final class NowoUxLinkExtension extends Extension
{
    /**
     * Tag collected by {@see LinkProviderRegistry}.
     */
    public const PROVIDER_TAG = 'nowo_ux_link.provider';

    /**
     * Loads the bundle services and exposes the processed configuration as parameters and as {@see BundleConfiguration}.
     *
     * @param array<int, array<string, mixed>> $configs Raw configuration from every config file
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        // Application services (autoconfigure: true) implementing the interface or carrying #[AsLinkProvider] join the
        // registry without an explicit tag. The attribute's priority orders the tagged iterator, so a provider with a
        // higher priority is registered first and wins over another provider with the same family and name.
        $container->registerForAutoconfiguration(LinkProviderInterface::class)
            ->addTag(self::PROVIDER_TAG);
        $container->registerAttributeForAutoconfiguration(
            AsLinkProvider::class,
            static function (ChildDefinition $definition, AsLinkProvider $attribute): void {
                $definition->addTag(self::PROVIDER_TAG, ['priority' => $attribute->priority]);
            },
        );

        $container->setParameter('nowo_ux_link.config', $config);

        $container->register(BundleConfiguration::class)
            ->setFactory([BundleConfiguration::class, 'fromArray'])
            ->setArguments([$config])
            ->setAutowired(false)
            ->setAutoconfigured(false);

        $container->setParameter('nowo_ux_link.templates.link', $config['templates']['link']);
        $container->setParameter('nowo_ux_link.templates.links', $config['templates']['links']);
        $container->setParameter('nowo_ux_link.templates.share_links', $config['templates']['share_links']);
        $container->setParameter('nowo_ux_link.templates.download_link', $config['templates']['download_link']);
    }

    /**
     * Returns the configuration root key (`nowo_ux_link`).
     */
    public function getAlias(): string
    {
        return 'nowo_ux_link';
    }
}
