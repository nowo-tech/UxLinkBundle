<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Enum;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Enum\LinkFamily
 */
final class LinkFamilyTest extends TestCase
{
    public function testTranslationPrefix(): void
    {
        self::assertSame('contact', LinkFamily::Contact->translationPrefix());
    }
}
