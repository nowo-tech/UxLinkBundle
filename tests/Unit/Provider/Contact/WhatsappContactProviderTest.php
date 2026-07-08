<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Tests\Unit\Provider\Contact;

use Nowo\UxLinkBundle\Model\Options\ContactOptions;
use Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nowo\UxLinkBundle\Provider\Contact\WhatsappContactProvider
 */
final class WhatsappContactProviderTest extends TestCase
{
    public function testCreatesWaMeUrlWithEncodedMessage(): void
    {
        $provider = new WhatsappContactProvider();
        $link = $provider->create(new ContactOptions(
            recipient: '+34600111222',
            message: 'Hola 👋',
        ));

        self::assertStringStartsWith('https://wa.me/34600111222', $link->url);
        self::assertStringContainsString('text=Hola', rawurldecode($link->url));
    }
}
