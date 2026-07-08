<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\DependencyInjection;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Loads bundle services and configuration.
 */
final class NowoUxLinkExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

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

    public function getAlias(): string
    {
        return 'nowo_ux_link';
    }
}
