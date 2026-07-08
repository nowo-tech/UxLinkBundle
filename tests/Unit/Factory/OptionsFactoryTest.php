<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Factory;

use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Factory\OptionsFactory;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Factory\OptionsFactory
 */
final class OptionsFactoryTest extends TestCase
{
    public function testUnknownFamilyThrows(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        (new OptionsFactory())->tryParseFamily('unknown');
    }

    public function testCreatesShareOptions(): void
    {
        $options = (new OptionsFactory())->create(
            LinkFamily::Share,
            ['url' => 'https://example.com'],
        );
        self::assertInstanceOf(ShareOptions::class, $options);
        self::assertSame('https://example.com', $options->url);
    }
}
