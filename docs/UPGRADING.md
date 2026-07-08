# Upgrading

## 1.0.0

First public release — there is no prior version to migrate from.

Install with:

```bash
composer require nowo-tech/ux-link-bundle:^1.0
```

Register `Nowo\UxLinkBundle\NowoUxLinkBundle` in `config/bundles.php` (Symfony Flex does this automatically).

Optional configuration:

```yaml
# config/packages/nowo_ux_link.yaml
nowo_ux_link:
    defaults:
        target: '_blank'
        rel: 'noopener noreferrer'
```

See [Installation](INSTALLATION.md) and [Configuration](CONFIGURATION.md) for full setup.
