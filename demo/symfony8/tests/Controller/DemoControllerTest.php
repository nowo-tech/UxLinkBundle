<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DemoControllerTest extends WebTestCase
{
    public function testHomePageReturns200AndShowsUxLinkComponents(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'UX Link Bundle Demo');
        self::assertSelectorExists('[data-testid="demo-platform"]');
        self::assertSelectorExists('[data-testid="contact-whatsapp"] a[href*="wa.me"]');
        self::assertSelectorExists('[data-testid="share-links"] .ux-share-links');
        self::assertSelectorExists('[data-testid="map-link"] a[href*="openstreetmap"]');
        self::assertSelectorExists('[data-testid="download-link"] .ux-download-link');
        self::assertSelectorExists('[data-testid="download-link"] a[href*="sample.pdf"]');
    }
}
