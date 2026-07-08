<?php

declare(strict_types=1);

use Nowo\UxLinkBundle\Contract\IconResolverInterface;
use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;
use Nowo\UxLinkBundle\Contract\LinkProviderInterface;
use Nowo\UxLinkBundle\Contract\LinkRendererInterface;
use Nowo\UxLinkBundle\Factory\LinkFactory;
use Nowo\UxLinkBundle\Registry\LinkProviderRegistry;
use Nowo\UxLinkBundle\Renderer\DefaultIconResolver;
use Nowo\UxLinkBundle\Renderer\HtmlLinkRenderer;
use Nowo\UxLinkBundle\Twig\Component;
use Nowo\UxLinkBundle\Twig\UxLinkExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->load('Nowo\\UxLinkBundle\\', __DIR__.'/../../')
        ->exclude([
            __DIR__.'/../../Attribute/',
            __DIR__.'/../../Config/BundleConfiguration.php',
            __DIR__.'/../../DependencyInjection/',
            __DIR__.'/../../NowoUxLinkBundle.php',
            __DIR__.'/../../Resources/',
        ]);

    $services->set(LinkProviderRegistry::class)
        ->arg('$providers', tagged_iterator('nowo_ux_link.provider'))
        ->public();

    $services->alias(LinkFactoryInterface::class, LinkFactory::class)->public();
    $services->alias(LinkRendererInterface::class, HtmlLinkRenderer::class)->public();
    $services->alias(IconResolverInterface::class, DefaultIconResolver::class);

    $services->set(HtmlLinkRenderer::class)
        ->arg('$defaultTemplate', '%nowo_ux_link.templates.link%');

    $services->instanceof(LinkProviderInterface::class)
        ->tag('nowo_ux_link.provider');

    $services->load('Nowo\\UxLinkBundle\\Provider\\', __DIR__.'/../../Provider/')
        ->tag('nowo_ux_link.provider');

    $services->set(UxLinkExtension::class)->tag('twig.extension');

    $services->set(Component\UxLink::class);
    $services->set(Component\UxLinks::class);
    $services->set(Component\UxShareLinks::class);
    $services->set(Component\UxDownloadLink::class);
};
