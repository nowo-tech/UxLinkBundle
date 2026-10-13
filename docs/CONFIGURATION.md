# Configuration

Full reference (all keys optional; values shown are the defaults unless noted):

```yaml
# config/packages/nowo_ux_link.yaml
nowo_ux_link:
    defaults:
        target: '_blank'                 # target applied by external_target_policy
        rel: 'noopener noreferrer'       # rel applied together with target
        show_icons: true                 # stored in BundleConfiguration::showIcons(); the bundle templates currently always render icons
        external_target_policy: auto     # auto | always | never
    families:                            # per family: contact | share | map | download
        map:
            enabled: false               # example: disable the whole map family
    providers:                           # per family, per provider name
        share:
            x:
                enabled: true
                icon: 'tabler:brand-x'   # overrides the resolved icon (null = DefaultIconResolver)
                label: 'Post on X'       # overrides the translated label (null = <family>.<provider>.label)
                priority: 0              # > 0 lets a custom provider with the same family/name replace the built-in one
    aliases:
        share:
            twitter: x                   # ux_link('share', 'twitter') resolves to the "x" provider
    templates:
        link: '@NowoUxLinkBundle/components/ux-link.html.twig'
        links: '@NowoUxLinkBundle/components/ux-links.html.twig'
        share_links: '@NowoUxLinkBundle/components/ux-share-links.html.twig'
        download_link: '@NowoUxLinkBundle/components/ux-download-link.html.twig'
```

## defaults

| Key | Default | Description |
| --- | --- | --- |
| `target` | `_blank` | `target` attribute added to links selected by `external_target_policy`. |
| `rel` | `noopener noreferrer` | `rel` attribute added together with `target`. |
| `show_icons` | `true` | Exposed as `BundleConfiguration::showIcons()` for custom renderers/templates. The bundle templates do not read it yet (they always render the icon when one is resolved). |
| `external_target_policy` | `auto` | `auto`: add `target`/`rel` only to absolute `http(s)` URLs. `always`: add them to every link (mailto/tel/relative included). `never`: never add them. |

## families

Map keyed by family name (`contact`, `share`, `map`, `download`). Each entry has `enabled` (default `true`). Creating a link of a disabled family throws `DisabledProviderException`.

## providers

Map keyed by family, then provider name. Built-in providers:

| Family | Providers |
| --- | --- |
| `contact` | `email`, `sms`, `telephone`, `whatsapp` |
| `share` | `email`, `linkedin`, `telegram`, `whatsapp`, `x` |
| `map` | `apple_maps`, `google_maps`, `openstreetmap`, `waze` |
| `download` | `download` |

| Key | Default | Description |
| --- | --- | --- |
| `enabled` | `true` | `false` makes `create()` throw `DisabledProviderException` for that provider. |
| `icon` | `null` | Icon name used instead of `DefaultIconResolver`. |
| `label` | `null` | Label used instead of the translated `<family>.<provider>.label`. An explicit `label` option on the link still wins. |
| `priority` | `0` | When two providers share family + name, the first registered wins unless the later one has `priority > 0`. Use it to replace a built-in provider with your own `#[AsLinkProvider]` service. |

## aliases

Map keyed by family, then alias => provider name (e.g. `share.twitter: x`). Aliases are resolved before the enabled check and the registry lookup.

## templates

| Key | Used by | Variables available in the template |
| --- | --- | --- |
| `link` | `HtmlLinkRenderer` (`ux_link()` Twig function, `LinkRendererInterface`) | `link`, `attributes`, `show_icon`, `show_label` |
| `links` | `<twig:UxLinks>` component | `links` (`LinkCollection`), `this` |
| `share_links` | `<twig:UxShareLinks>` component | `links` (`LinkCollection`), `this` |
| `download_link` | `<twig:UxDownloadLink>` component | `link`, `this` (`this.humanSize()`) |

The component templates are applied at container compile time (they replace the template declared in `#[AsTwigComponent]`). You can also override any bundle template by placing a file under `templates/bundles/NowoUxLinkBundle/`.

## Translations

The bundle uses the `NowoUxLinkBundle` translation domain.

### Supported locales

| Locale | Language |
| ------ | -------- |
| `en` | English (reference catalogue) |
| `es` | Spanish |
| `it` | Italian |
| `fr` | French |
| `pt` | Portuguese |
| `de` | German |
| `nl` | Dutch |

### Overriding translations (REQ-I18N-001)

Create app translation files with the same domain and locale, for example:

```yaml
# translations/NowoUxLinkBundle.es.yaml
contact.whatsapp.label: 'Escríbenos por WhatsApp'
```

Symfony loads application translations first; missing keys fall back to the bundle catalogue under `src/Resources/translations/`.
