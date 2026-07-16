# Upgrading

## 1.0.4 → 1.0.5

No action required for application integrators. This patch adds repository community standards and Git/CI hygiene (Code of Conduct, REQ-GIT-001).

Contributors cloning the repo should install hooks once:

```bash
composer update nowo-tech/ux-link-bundle
make setup-hooks
```

## 1.0.3 → 1.0.4

No action required. This patch release only updates repository housekeeping (`.gitignore` for local Cursor sandbox config).

```bash
composer update nowo-tech/ux-link-bundle
```

## 1.0.2 → 1.0.3

No action required. This patch release aligns the README and repository housekeeping with Nowo bundle standards.

```bash
composer update nowo-tech/ux-link-bundle
```

## 1.0.1 → 1.0.2

No action required. This patch release fixes CI integration tests and improves documentation and translation validation tooling.

```bash
composer update nowo-tech/ux-link-bundle
```

## 1.0.0 → 1.0.1

No action required. This patch release only fixes the GitHub Actions CI workflow.

```bash
composer update nowo-tech/ux-link-bundle
```

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
