<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Model;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\LinkAttributes;
use Nowo\UxLinkBundle\Model\LinkCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Model\Link
 * @covers \Nowo\UxLinkBundle\Model\LinkAttributes
 * @covers \Nowo\UxLinkBundle\Model\LinkCollection
 */
final class LinkModelTest extends TestCase
{
    public function testLinkStringableAndWithMethods(): void
    {
        $link = new Link(LinkFamily::Share, 'x', 'https://example.com');
        self::assertSame('https://example.com', (string) $link);

        $updated = $link->withLabel('Share')->withAttributes(new LinkAttributes(class: 'x'));
        self::assertSame('Share', $updated->label);
        self::assertSame('x', $updated->attributes->class);
    }

    public function testLinkCollection(): void
    {
        $a = new Link(LinkFamily::Contact, 'email', 'mailto:a@b.com');
        $collection = (new LinkCollection())->add($a);
        self::assertCount(1, $collection);
        self::assertSame([$a], $collection->all());
    }
}
