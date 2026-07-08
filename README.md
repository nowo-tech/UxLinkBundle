# UX Link Bundle

[![CI](https://github.com/nowo-tech/UxLinkBundle/actions/workflows/ci.yml/badge.svg)](https://github.com/nowo-tech/UxLinkBundle/actions/workflows/ci.yml)
[![Packagist Version](https://img.shields.io/packagist/v/nowo-tech/ux-link-bundle.svg?style=flat)](https://packagist.org/packages/nowo-tech/ux-link-bundle)
[![Packagist Downloads](https://img.shields.io/packagist/dt/nowo-tech/ux-link-bundle.svg)](https://packagist.org/packages/nowo-tech/ux-link-bundle)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)](https://php.net)
[![Symfony](https://img.shields.io/badge/Symfony-7.4%20%7C%208.0%20%7C%208.1%2B-000000?logo=symfony)](https://symfony.com)
[![GitHub stars](https://img.shields.io/github/stars/nowo-tech/UxLinkBundle.svg?style=social&label=Star)](https://github.com/nowo-tech/UxLinkBundle)
[![Coverage](https://img.shields.io/badge/Coverage-~100%25-green)](#tests-and-coverage)

Generate safe, extensible contact, share, map, and download links for Symfony applications.

## Installation

```bash
composer require nowo-tech/ux-link-bundle
```

Register the bundle (Symfony Flex does this automatically):

```php
// config/bundles.php
Nowo\UxLinkBundle\NowoUxLinkBundle::class => ['all' => true],
```

## Quick example (PHP)

```php
use Nowo\UxLinkBundle\Contract\LinkFactoryInterface;

$link = $linkFactory->create('contact', 'whatsapp', [
    'recipient' => '+34600111222',
    'message' => 'Hello',
]);
echo $link->getUrl();
```

## Quick example (Twig)

```twig
{{ ux_link_url('contact', 'whatsapp', { recipient: '+34600111222', message: 'Hello' }) }}
```

## Quick example (Twig Component)

```twig
<twig:UxLink family="contact" provider="whatsapp" recipient="+34600111222" message="Hello" />
```

## Documentation

- [Installation](docs/INSTALLATION.md)
- [Configuration](docs/CONFIGURATION.md)
- [Usage](docs/USAGE.md)
- [Contributing](docs/CONTRIBUTING.md)
- [Changelog](docs/CHANGELOG.md)
- [Upgrading](docs/UPGRADING.md)
- [Release](docs/RELEASE.md)
- [Security](docs/SECURITY.md)
- [Engram](docs/ENGRAM.md)
- [Spec-driven development](docs/SPEC-DRIVEN-DEVELOPMENT.md)
- [GitHub Spec Kit](docs/SPEC-KIT.md)

FrankenPHP worker mode: Supported (see [DEMO-FRANKENPHP](docs/DEMO-FRANKENPHP.md)).

## Version information

| Version | PHP | Symfony | Status |
|---------|-----|---------|--------|
| 1.0.x | >= 8.2 | 7.0 – 8.1+ | Supported |

Install a specific release:

```bash
composer require nowo-tech/ux-link-bundle:^1.0
```

## Demos

```bash
make -C demo up-symfony7   # http://localhost:8047
make -C demo up-symfony8   # http://localhost:8048
```

## Tests and coverage

```bash
make test
make test-coverage
```

## Found this useful?

Give it a star on [GitHub](https://github.com/nowo-tech/UxLinkBundle).

## License

MIT — see [LICENSE](LICENSE).
