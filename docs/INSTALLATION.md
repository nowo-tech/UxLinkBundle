# Installation

```bash
composer require nowo-tech/ux-link-bundle
```

Ensure `NowoUxLinkBundle` is registered in `config/bundles.php`.

Requirements: PHP >= 8.2, Symfony >= 7.0, `symfony/ux-twig-component`.

## Twig Extra Bundle (REQ-TWIG-004)

This package ships Twig templates. Host applications **must** install and enable Twig Extra:

```bash
composer require twig/extra-bundle twig/string-extra
```

Register `Twig\Extra\TwigExtraBundle\TwigExtraBundle` in `config/bundles.php` (Flex usually does this). Demos already include the same stack. The package `release-check` runs `make check-twig-extra` to guard this contract.
