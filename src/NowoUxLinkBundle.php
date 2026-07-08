<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle;

use Nowo\UxLinkBundle\DependencyInjection\Compiler\TwigPathsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony bundle for generating safe, extensible UX links.
 */
final class NowoUxLinkBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new TwigPathsPass());
    }
}
