# Usage

## Table of contents

- [PHP](#php)
- [Twig functions](#twig-functions)
- [Twig Components](#twig-components)
- [Custom providers](#custom-providers)
- [Overriding templates (REQ-TWIG-001)](#overriding-templates-req-twig-001)

## PHP

```php
$link = $linkFactory->create('share', 'linkedin', ['url' => 'https://example.com']);
```

## Twig functions

- `ux_link_url(family, provider, options)`
- `ux_link(family, provider, options, attributes)`
- `ux_links(family, providers, options)`

## Twig Components

- `<twig:UxLink />`
- `<twig:UxLinks />`
- `<twig:UxShareLinks />`
- `<twig:UxDownloadLink />`

## Custom providers

Implement `LinkProviderInterface` and tag the service with `nowo_ux_link.provider` (or `#[AsLinkProvider]`). Do **not** mutate `LinkProviderRegistry` at runtime — registration is constructor-only (FrankenPHP worker safe).

## FrankenPHP worker mode

This bundle is safe with FrankenPHP worker mode when the kernel is **not** reset between requests. See [FRANKENPHP-WORKER-AUDIT.md](FRANKENPHP-WORKER-AUDIT.md).

## Overriding templates (REQ-TWIG-001)

Place overrides under `templates/bundles/NowoUxLinkBundle/components/`.

| Subpath | Purpose |
|---------|---------|
| `components/ux-link.html.twig` | Single link anchor |
| `components/ux-links.html.twig` | Link list |
| `components/ux-share-links.html.twig` | Share navigation |
| `components/ux-download-link.html.twig` | Download card |
