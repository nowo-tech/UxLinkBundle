<?php

declare(strict_types=1);

namespace Nowo\UxLinkBundle\Renderer;

use Nowo\UxLinkBundle\Contract\IconResolverInterface;
use Nowo\UxLinkBundle\Model\Link;

/**
 * Resolves default logical icon names per provider.
 */
final class DefaultIconResolver implements IconResolverInterface
{
    /** @var array<string, array<string, string>> */
    private const DEFAULTS = [
        'contact' => [
            'whatsapp' => 'fa6-brands:whatsapp',
            'email' => 'fa6-solid:envelope',
            'telephone' => 'fa6-solid:phone',
            'sms' => 'fa6-solid:comment-sms',
        ],
        'share' => [
            'linkedin' => 'fa6-brands:linkedin',
            'x' => 'fa6-brands:x-twitter',
            'whatsapp' => 'fa6-brands:whatsapp',
            'telegram' => 'fa6-brands:telegram',
            'email' => 'fa6-solid:envelope',
        ],
        'map' => [
            'google_maps' => 'fa6-brands:google',
            'apple_maps' => 'fa6-brands:apple',
            'waze' => 'fa6-brands:waze',
            'openstreetmap' => 'fa6-solid:map',
        ],
        'download' => [
            'download' => 'fa6-solid:download',
        ],
    ];

    public function resolve(Link $link): ?string
    {
        return self::DEFAULTS[$link->family->value][$link->provider] ?? null;
    }
}
