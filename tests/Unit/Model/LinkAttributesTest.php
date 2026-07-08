<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Model;

use Nowo\UxLinkBundle\Model\LinkAttributes;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Model\LinkAttributes
 */
final class LinkAttributesTest extends TestCase
{
    public function testFromArrayMergeAndToArray(): void
    {
        $base = LinkAttributes::fromArray([
            'target' => '_self',
            'class' => 'a',
            'aria-label' => 'Open',
            'download' => 'file.pdf',
            'data-id' => '1',
            'ignored' => 123,
        ]);
        $merged = $base->merge(new LinkAttributes(target: '_blank', class: 'b'));

        self::assertSame('_blank', $merged->target);
        self::assertSame('a b', $merged->class);
        $array = $merged->toArray();
        self::assertSame('_blank', $array['target']);
        self::assertSame('a b', $array['class']);
        self::assertSame('Open', $array['aria-label']);
        self::assertSame('file.pdf', $array['download']);
        self::assertSame('1', $array['data-id']);
    }
}
