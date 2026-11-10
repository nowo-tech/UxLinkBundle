<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Kernel;

use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyProvider;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Test kernel with application-style providers registered through autoconfigure only (no explicit tag).
 */
final class HostProvidersKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__.'/../Fixtures/app';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/host_providers_'.$this->environment;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import($this->getConfigDir().'/{packages}/*.{php,yaml}');

        $services = $container->services()->defaults()->autowire()->autoconfigure();
        $services->set(AttributeOnlyEmailProvider::class);
        $services->set(InterfaceOnlyProvider::class);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }
}
