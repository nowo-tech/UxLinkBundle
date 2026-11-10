# Usage

## Table of contents

- [PHP](#php)
- [Twig functions](#twig-functions)
- [Twig Components](#twig-components)
- [Custom providers](#custom-providers)
- [Using it from a DDD / hexagonal application](#using-it-from-a-ddd--hexagonal-application)
- [FrankenPHP worker mode](#frankenphp-worker-mode)
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

Implement `LinkProviderInterface` (or extend `Provider\AbstractLinkProvider`) and register the service with `#[AsLinkProvider]` (services with `autoconfigure: true`, the Symfony default for `App\` services) or with the `nowo_ux_link.provider` tag. Implementing the interface alone does **not** register the service. Do **not** mutate `LinkProviderRegistry` at runtime — registration is constructor-only (FrankenPHP worker safe).

```php
use Nowo\UxLinkBundle\Attribute\AsLinkProvider;
use Nowo\UxLinkBundle\Enum\LinkFamily;
use Nowo\UxLinkBundle\Model\Link;
use Nowo\UxLinkBundle\Model\Options\LinkOptionsInterface;
use Nowo\UxLinkBundle\Provider\AbstractLinkProvider;

#[AsLinkProvider(priority: 10)] // > 0: replaces the built-in share/x provider
final class MyXShareProvider extends AbstractLinkProvider
{
    public function getFamily(): LinkFamily { return LinkFamily::Share; }
    public function getName(): string { return 'x'; }
    public function create(LinkOptionsInterface $options): Link { /* ... */ }
}
```

When two providers share a family and name:

- Providers are registered in tag priority order (highest first) and the **first** one wins.
- The built-in providers have priority 0 and win ties: `#[AsLinkProvider]` with priority 0 (the default) or less only
  adds new names; `#[AsLinkProvider(priority: N > 0)]` replaces a built-in provider.
- An explicit tag's `priority` overrides the attribute's (Symfony uses the first tag). An explicitly tagged provider
  with priority 0 is registered before the built-in one and replaces it, as in 1.2.1.
- `providers.<family>.<name>.priority: N > 0` in the bundle configuration makes an application provider replace the
  built-in one with that name, however it was registered.

## Using it from a DDD / hexagonal application

UX Link is a **presentation-layer** bundle: it turns scalar input (an email, a phone number, a URL, coordinates)
into immutable `Link` value objects and renders them in Twig. It has no Doctrine dependency, no entities and no
listeners, so your domain never depends on it.

- Keep the domain free of it: expose plain values (e.g. `$contact->email()->toString()`) to your view model or
  controller and build links there, or directly in Twig with `ux_link()` / `<twig:UxLink />`.
- From PHP, depend on the interfaces, not the concrete classes:
  `Nowo\UxLinkBundle\Contract\LinkFactoryInterface` and `Contract\LinkRendererInterface`.
- `Model\Link`, `LinkCollection` and the options DTOs are `readonly` value objects; you can pass them to templates safely.
- Extension points: implement `Contract\LinkProviderInterface` (or extend `Provider\AbstractLinkProvider`) for a new
  provider inside an existing family (register it with `#[AsLinkProvider]` or the tag, see
  [Custom providers](#custom-providers)), and re-alias
  `Contract\IconResolverInterface` to change icons.

```yaml
# config/services.yaml — only needed when the service is not autoconfigured
App\Ui\Link\MastodonShareProvider:
    autoconfigure: false
    tags: [{ name: 'nowo_ux_link.provider', priority: 0 }]
```

### Known limitations

- Families are fixed (`contact`, `share`, `map`, `download`); a provider cannot add a new family or options type.
- The factory API is string-based (`create(string $family, string $provider, array $options)`); the typed options DTOs
  are built internally.

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

**Override vs upgrade (freeze rule).** An application file at the override path always wins and does not pick up vendor changes for that path until you remove or merge it: a full-file override freezes that `<subpath>` on every `composer update` while the rest of the bundle keeps upgrading. Prefer the upgrade-safe options first: `nowo_ux_link.templates.*` pointing at your **own** template names (the component then renders your file and the vendor default stays untouched), link attributes / classes from the factory options, and provider icons / labels in `providers.*`.

**Recovering upstream after a freeze:** delete (or rename aside) the application override, clear the cache (`php bin/console cache:clear`) and re-apply only the customisation you still need. Diff your copy against the new vendor file under `vendor/nowo-tech/ux-link-bundle/src/Resources/views/` before re-copying.
