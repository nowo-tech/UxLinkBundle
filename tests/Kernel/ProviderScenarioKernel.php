<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Kernel;

use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeNegativeEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\AttributeZeroEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyEmailProvider;
use Nowo\UxLinkBundle\Tests\Fixtures\Provider\InterfaceOnlyTelephoneProvider;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Test kernel whose environment name selects how a host provider for a built-in name is registered.
 *
 * Every service is defined with `autowire` + `autoconfigure`, as in a standard Symfony application.
 */
final class ProviderScenarioKernel extends Kernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__.'/../Fixtures/app';
    }

    public function getCacheDir(): string
    {
        return $this->getProjectDir().'/var/cache/provider_scenario_'.$this->environment;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import($this->getConfigDir().'/{packages}/*.{php,yaml}');

        $services = $container->services()->defaults()->autowire()->autoconfigure();

        switch ($this->environment) {
            case 'interface_only':
                $services->set(InterfaceOnlyEmailProvider::class);
                $services->set(InterfaceOnlyTelephoneProvider::class);
                break;
            case 'attribute_zero':
                $services->set(AttributeZeroEmailProvider::class);
                break;
            case 'attribute_negative':
                $services->set(AttributeNegativeEmailProvider::class);
                break;
            case 'attribute_positive':
                $services->set(AttributeOnlyEmailProvider::class);
                break;
            case 'explicit_tag':
                $services->set(InterfaceOnlyEmailProvider::class)->tag('nowo_ux_link.provider');
                break;
            case 'explicit_tag_priority':
                $services->set(InterfaceOnlyEmailProvider::class)->tag('nowo_ux_link.provider', ['priority' => 5]);
                break;
            case 'explicit_tag_overrides_attribute':
                $services->set(AttributeNegativeEmailProvider::class)->tag('nowo_ux_link.provider');
                break;
            case 'config_priority_explicit_tag':
                $services->set(InterfaceOnlyEmailProvider::class)->tag('nowo_ux_link.provider');
                $this->configurePriority($container);
                break;
            case 'config_priority_attribute_zero':
                $services->set(AttributeZeroEmailProvider::class);
                $this->configurePriority($container);
                break;
            case 'config_priority_attribute_positive':
                $services->set(AttributeOnlyEmailProvider::class);
                $this->configurePriority($container);
                break;
            case 'config_priority_attribute_and_tag':
                $services->set(AttributeOnlyEmailProvider::class)->tag('nowo_ux_link.provider');
                $this->configurePriority($container);
                break;
            case 'scan':
                $services->load('Nowo\\UxLinkBundle\\Tests\\Fixtures\\ProviderScan\\', __DIR__.'/../Fixtures/ProviderScan/');
                break;
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
    }

    private function configurePriority(ContainerConfigurator $container): void
    {
        $container->extension('nowo_ux_link', ['providers' => ['contact' => ['email' => ['priority' => 10]]]]);
    }
}
