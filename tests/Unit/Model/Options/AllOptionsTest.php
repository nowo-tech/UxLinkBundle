<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Model\Options;

use Nowo\UxLinkBundle\Enum\MapAction;
use Nowo\UxLinkBundle\Exception\InvalidLinkOptionsException;
use Nowo\UxLinkBundle\Exception\InvalidUrlException;
use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Model\Options\DownloadOptions;
use Nowo\UxLinkBundle\Model\Options\MapOptions;
use Nowo\UxLinkBundle\Model\Options\ShareOptions;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Model\Options\ContactOptions
 * @covers \Nowo\UxLinkBundle\Model\Options\ShareOptions
 * @covers \Nowo\UxLinkBundle\Model\Options\MapOptions
 * @covers \Nowo\UxLinkBundle\Model\Options\DownloadOptions
 */
final class AllOptionsTest extends TestCase
{
    public function testContactOptionsFromArray(): void
    {
        $options = ContactOptions::fromArray([
            'email' => 'a@b.com',
            'message' => 'Hi',
            'subject' => 'S',
            'cc' => ['c@b.com'],
            'bcc' => ['b@b.com'],
            'label' => 'Mail',
        ]);

        self::assertSame('a@b.com', $options->recipient);
        self::assertSame(['c@b.com'], $options->cc);
    }

    public function testContactOptionsMissingRecipient(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        ContactOptions::fromArray([]);
    }

    public function testShareOptionsRejectsJavascriptUrl(): void
    {
        $this->expectException(InvalidUrlException::class);
        ShareOptions::fromArray(['url' => 'javascript:alert(1)']);
    }

    public function testMapOptionsRouteWithOriginDestination(): void
    {
        $options = MapOptions::fromArray([
            'action' => 'route',
            'origin' => 'Madrid',
            'destination' => 'Barcelona',
        ]);

        self::assertSame(MapAction::Route, $options->action);
        self::assertSame('Madrid', $options->origin);
    }

    public function testMapOptionsMissingLocation(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        MapOptions::fromArray(['action' => 'view']);
    }

    public function testDownloadOptionsWithStringSize(): void
    {
        $options = DownloadOptions::fromArray([
            'url' => '/file.pdf',
            'filename' => 'file.pdf',
            'size' => '1024',
            'previewUrl' => 'https://example.com/preview',
        ]);

        self::assertSame(1024, $options->size);
    }

    public function testDownloadOptionsMissingUrl(): void
    {
        $this->expectException(InvalidLinkOptionsException::class);
        DownloadOptions::fromArray([]);
    }
}
