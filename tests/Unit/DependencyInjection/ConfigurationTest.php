<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\DependencyInjection;

use Nowo\UxLinkBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @covers \Nowo\UxLinkBundle\DependencyInjection\Configuration
 */
final class ConfigurationTest extends TestCase
{
    public function testDefaultConfigurationTree(): void
    {
        $processed = (new Processor())->processConfiguration(new Configuration(), [[
            'providers' => [
                'contact' => ['whatsapp' => ['enabled' => true, 'icon' => 'icon']],
            ],
            'aliases' => ['share' => ['twitter' => 'x']],
        ]]);

        self::assertSame('_blank', $processed['defaults']['target']);
        self::assertTrue($processed['defaults']['show_icons']);
        self::assertSame('x', $processed['aliases']['share']['twitter']);
    }
}
