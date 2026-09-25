# Upgrading

## Table of contents

- [1.1.3 → 1.1.4](#113--114)
- [1.1.2 → 1.1.3](#112--113)
- [To 1.1.2](#to-112)
- [To 1.1.1](#to-111)
- [To 1.1.0](#to-110)
- [1.0.x patch notes](#10x-patch-notes)

## 1.1.3 → 1.1.4

FrankenPHP worker hardening (`reset_kernel` false / scenario B).

**Usually no action** if you register custom providers as tagged services (`nowo_ux_link.provider` / `#[AsLinkProvider]`).

**Breaking only if you called** `LinkProviderRegistry::add()` at runtime (unsupported / undocumented). Registration is now constructor-only. Migrate to a tagged service.

Twig components are also explicitly `share(false)` in DI (defensive; UX TwigComponent already did this).

```bash
composer update nowo-tech/ux-link-bundle
php bin/console cache:clear
```

See [FRANKENPHP-WORKER-AUDIT.md](FRANKENPHP-WORKER-AUDIT.md).

## 1.1.2 → 1.1.3

No breaking changes. **No application upgrade steps.**

```bash
composer update nowo-tech/ux-link-bundle
```

## To 1.1.2

No application upgrade steps.

```bash
composer update nowo-tech/ux-link-bundle
```

## To 1.1.1

No application upgrade steps. **Demos only:** Hot Reload Bundle `^1.4` (FrankenPHP Mercure/`hot_reload`, `dev`/`test`). Shipped demos are Symfony 8 only (Symfony 6/7 demo apps removed).

```bash
composer update nowo-tech/ux-link-bundle
php bin/console cache:clear
```

## To 1.1.0

From **1.0.7** — Adds required Twig Extra (REQ-TWIG-004) and Twig-CS-Fixer. Register TwigExtraBundle if Flex did not.

```bash
composer update nowo-tech/ux-link-bundle
php bin/console cache:clear
```

### Twig Extra Bundle (REQ-TWIG-004)

Hosts that render this bundle's Twig templates must install:

```bash
composer require twig/extra-bundle twig/string-extra
```

and enable `Twig\Extra\TwigExtraBundle\TwigExtraBundle`. Flex recipes usually register it automatically.

### Twig-CS-Fixer (maintainers)

Package maintainers: `composer twig:lint` / `composer twig:fix` use `.twig-cs-fixer.php` over `src/` (and `templates/` when present).

## 1.0.x patch notes

### 1.0.6 → 1.0.7

No action required for application integrators. Maintainer tooling, FrankenPHP Friendly banner, PHPUnit deprecation helper, Packagist metadata.

### 1.0.5 → 1.0.6

No action required. Demos: if you relied on `APP_ENV=dev` for classic FrankenPHP, set `FRANKENPHP_MODE=classic` and recreate the container.

### 1.0.4 → 1.0.5

No action required. Contributors: `make setup-hooks` once (REQ-GIT-001).

### 1.0.0 → 1.0.4

Housekeeping / CI / docs only — no application migration.

### 1.0.0

First public release. Install with `composer require nowo-tech/ux-link-bundle:^1.0`.
