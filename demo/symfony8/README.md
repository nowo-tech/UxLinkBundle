# Symfony 8.1 demo — UX Link Bundle

FrankenPHP + Web Profiler + **Twig Inspector** + Twig Components for contact, share, map, and download links.

## Quick start

```bash
cp .env.example .env   # if needed
make up
```

Open the URL printed by `make up` (`Demo started at: http://localhost:<PORT>`; default **8048**).

## What the demo shows

The home page (`/`) renders:

- **Contact** — WhatsApp link via `<twig:UxLink />`
- **Share** — LinkedIn, X, WhatsApp, Telegram, and email via `<twig:UxShareLinks />`
- **Map** — OpenStreetMap link via `<twig:UxLink family="map" />`
- **Download** — enriched download card via `<twig:UxDownloadLink />`

## Make targets

| Target | Purpose |
|--------|---------|
| `make up` | Start containers, install dependencies, print demo URL |
| `make down` | Stop containers |
| `make test` | Run PHPUnit (updates bundle first) |
| `make verify` | HTTP 200 healthcheck on `/` |
| `make update-bundle` | `composer update nowo-tech/ux-link-bundle` inside the container |

## Bundle source

The mounted path repo is `/var/ux-link-bundle` (bundle root from `../..`). Edit the bundle locally and run `make update-bundle` or reload after `composer install`.

## Tests

```bash
make test
```

Runs `tests/Controller/DemoControllerTest.php` against the demo kernel.
