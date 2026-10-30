<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle;

use Nowo\UxLinkBundle\DependencyInjection\Compiler\ComponentTemplatesPass;
use Nowo\UxLinkBundle\DependencyInjection\Compiler\TwigPathsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony bundle for generating safe, extensible UX links.
 */
final class NowoUxLinkBundle extends Bundle
{
    /**
     * Registers the Twig paths and component template compiler passes.
     *
     * @param ContainerBuilder $container Container being built
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new TwigPathsPass());
        // After autoconfiguration (priority 100), before UX TwigComponent's pass (priority 0).
        $container->addCompilerPass(new ComponentTemplatesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }
}
