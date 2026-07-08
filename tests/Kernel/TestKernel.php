<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Kernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Minimal kernel for integration tests.
 */
final class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return \dirname(__DIR__).'/Fixtures/app';
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }
}
