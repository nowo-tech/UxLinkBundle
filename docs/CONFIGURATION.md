# Configuration

```yaml
# config/packages/nowo_ux_link.yaml
nowo_ux_link:
    defaults:
        target: '_blank'
        rel: 'noopener noreferrer'
        show_icons: true
    aliases:
        share:
            twitter: x
```

See `Configuration.php` for the full tree.

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
