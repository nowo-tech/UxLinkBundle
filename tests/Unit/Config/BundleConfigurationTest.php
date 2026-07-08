<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Config;

use Nowo\UxLinkBundle\Config\BundleConfiguration;
use Nowo\UxLinkBundle\Enum\ExternalTargetPolicy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Config\BundleConfiguration
 */
final class BundleConfigurationTest extends TestCase
{
    public function testFromArrayAndAccessors(): void
    {
        $config = BundleConfiguration::fromArray([
            'defaults' => [
                'target' => '_blank',
                'rel' => 'noopener',
                'show_icons' => false,
                'external_target_policy' => 'never',
            ],
            'families' => ['contact' => ['enabled' => false]],
            'providers' => ['share' => ['x' => ['enabled' => false, 'priority' => 5, 'icon' => 'custom']]],
            'aliases' => ['share' => ['twitter' => 'x']],
        ]);

        self::assertFalse($config->isFamilyEnabled('contact'));
        self::assertTrue($config->isFamilyEnabled('share'));
        self::assertFalse($config->isProviderEnabled('share', 'x'));
        self::assertSame('x', $config->resolveProviderAlias('share', 'twitter'));
        self::assertSame(['enabled' => false, 'priority' => 5, 'icon' => 'custom'], $config->providerConfig('share', 'x'));
        self::assertSame('_blank', $config->defaultTarget());
        self::assertSame('noopener', $config->defaultRel());
        self::assertFalse($config->showIcons());
        self::assertSame(ExternalTargetPolicy::Never, $config->externalTargetPolicy());
    }

    public function testInvalidPolicyFallsBackToAuto(): void
    {
        $config = new BundleConfiguration(['external_target_policy' => 'invalid'], [], [], []);
        self::assertSame(ExternalTargetPolicy::Auto, $config->externalTargetPolicy());
    }
}
