<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Security;

use Nowo\UxLinkBundle\Security\HtmlAttributePolicy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Security\HtmlAttributePolicy
 */
final class HtmlAttributePolicyTest extends TestCase
{
    public function testFiltersUnsafeAttributes(): void
    {
        $filtered = HtmlAttributePolicy::filter([
            'class' => 'btn',
            'onclick' => 'alert(1)',
            'data-track' => 'share',
        ]);

        self::assertSame(['class' => 'btn', 'data-track' => 'share'], $filtered);
    }

    public function testSanitizeValueStripsJavascript(): void
    {
        self::assertSame('', HtmlAttributePolicy::sanitizeValue('javascript:alert(1)'));
        self::assertSame('hello', HtmlAttributePolicy::sanitizeValue('hello'));
    }
}
