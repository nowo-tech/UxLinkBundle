<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\DependencyInjection\Compiler;

use Nowo\UxLinkBundle\DependencyInjection\Compiler\TwigPathsPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @covers \Nowo\UxLinkBundle\DependencyInjection\Compiler\TwigPathsPass
 */
final class TwigPathsPassTest extends TestCase
{
    public function testAddsBundleViewsPath(): void
    {
        $tmp = sys_get_temp_dir().'/ux_link_twig_'.bin2hex(random_bytes(4));
        self::assertTrue(mkdir($tmp, 0777, true));

        try {
            $container = new ContainerBuilder();
            $container->setParameter('kernel.project_dir', $tmp);
            $loaderDef = new Definition();
            $container->setDefinition('twig.loader.native_filesystem', $loaderDef);

            (new TwigPathsPass())->process($container);

            $viewsPath = \dirname(__DIR__, 4).'/src/Resources/views';
            self::assertContains(
                ['addPath', [$viewsPath, 'NowoUxLinkBundle']],
                $loaderDef->getMethodCalls(),
            );
        } finally {
            $this->removeDir($tmp);
        }
    }

    public function testPrependsOverridePathWhenDirectoryExists(): void
    {
        $tmp = sys_get_temp_dir().'/ux_link_twig_'.bin2hex(random_bytes(4));
        $override = $tmp.'/templates/bundles/NowoUxLinkBundle';
        self::assertTrue(mkdir($override, 0777, true));

        try {
            $container = new ContainerBuilder();
            $container->setParameter('kernel.project_dir', $tmp);
            $loaderDef = new Definition();
            $container->setDefinition('twig.loader.native', $loaderDef);

            (new TwigPathsPass())->process($container);

            self::assertContains(
                ['prependPath', [$override, 'NowoUxLinkBundle']],
                $loaderDef->getMethodCalls(),
            );
        } finally {
            $this->removeDir($tmp);
        }
    }

    public function testResolvesTwigLoaderAliasChain(): void
    {
        $container = new ContainerBuilder();
        $loaderDef = new Definition();
        $container->setDefinition('twig.loader.native_filesystem', $loaderDef);
        $container->setAlias('twig.loader.native', 'twig.loader.native_filesystem');

        (new TwigPathsPass())->process($container);

        self::assertNotEmpty($loaderDef->getMethodCalls());
    }

    public function testReturnsEarlyWhenAliasDoesNotResolveToDefinition(): void
    {
        self::expectNotToPerformAssertions();

        $container = new ContainerBuilder();
        $container->setAlias('twig.loader.native', 'missing.loader');

        (new TwigPathsPass())->process($container);
    }

    public function testSkipsWhenNoTwigLoader(): void
    {
        self::expectNotToPerformAssertions();

        $container = new ContainerBuilder();

        (new TwigPathsPass())->process($container);
    }

    public function testIgnoresNonStringProjectDir(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', 123);
        $loaderDef = new Definition();
        $container->setDefinition('twig.loader.native_filesystem', $loaderDef);

        (new TwigPathsPass())->process($container);

        self::assertCount(1, $loaderDef->getMethodCalls());
    }

    private function removeDir(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        foreach (scandir($path) ?: [] as $item) {
            if ('.' === $item || '..' === $item) {
                continue;
            }
            $full = $path.'/'.$item;
            is_dir($full) ? $this->removeDir($full) : @unlink($full);
        }
        @rmdir($path);
    }
}
