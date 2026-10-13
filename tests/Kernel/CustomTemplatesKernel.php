<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Test kernel with custom component templates (templates.* config).
 */
final class CustomTemplatesKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__.'/../Fixtures/app';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/custom_templates_'.$this->environment;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import($this->getConfigDir().'/{packages}/*.{php,yaml}');
        $container->extension('nowo_ux_link', [
            'templates' => [
                'links' => 'custom/ux-links.html.twig',
                'share_links' => 'custom/ux-share-links.html.twig',
                'download_link' => 'custom/ux-download-link.html.twig',
            ],
        ]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }
}
