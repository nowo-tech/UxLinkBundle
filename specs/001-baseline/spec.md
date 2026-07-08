# Feature Specification: UxLinkBundle baseline (100% code coverage)

**Feature Branch**: `001-baseline`  
**Created**: 2026-07-07  
**Status**: Active  
**Input**: Backfill GitHub Spec Kit baseline documenting 100% of production code in `src/`.

**Related docs**: [`docs/SPEC-DRIVEN-DEVELOPMENT.md`](../../docs/SPEC-DRIVEN-DEVELOPMENT.md), [`docs/CONFIGURATION.md`](../../docs/CONFIGURATION.md), [`docs/USAGE.md`](../../docs/USAGE.md)  
**Code inventory (traceability)**: [`code-inventory.md`](code-inventory.md)

---

## Summary

**Package**: `nowo-tech/ux-link-bundle`  
**Configuration root**: `nowo_ux_link`


Symfony bundle for **safe, extensible link generation**: contact (email, phone, SMS, WhatsApp), share (social), map (Google, Apple, Waze, OSM), and download links via providers, Twig helpers, and UX components.

---

## User Scenarios & Testing

### US-01 — Render contact/share/map/download links (Priority: P1)

As an integrator, I resolve links by provider id and options, then render HTML or URLs in Twig.

**Acceptance**: `LinkProviderRegistry` → `LinkFactory` → `HtmlLinkRenderer` / `UrlRenderer`.

### US-02 — Extend with custom providers (Priority: P1)

As an integrator, I register tagged providers via `#[AsLinkProvider]` without forking the bundle.

**Acceptance**: Autoconfiguration in `services.php`; `ProviderNotFoundException` when missing.

### US-03 — Enforce URL and attribute safety (Priority: P1)

As a security-conscious integrator, I rely on `UrlPolicy` and `HtmlAttributePolicy` to block dangerous schemes and attributes.

### US-04 — Use Twig components (Priority: P2)

As a frontend developer, I embed `<twig:ux-link>`, share grids, and download components.

**Acceptance**: `UxLink`, `UxLinks`, `UxShareLinks`, `UxDownloadLink` components + templates.

---

## Requirements

### Bundle & providers

- **FR-BUNDLE-001 / FR-CFG-001 / FR-CFG-002**: Bundle entry, config tree (`enabled_providers`, policies), extension.
- **FR-API-001**: Public contracts (`LinkProviderInterface`, `LinkFactoryInterface`, `LinkRendererInterface`, `IconResolverInterface`).
- **FR-PROV-001**: Bundled providers for Contact, Share, Map, Download families.
- **FR-FACT-001**: `LinkFactory`, `OptionsFactory`, `LinkProviderRegistry`.
- **FR-RENDER-001**: `HtmlLinkRenderer`, `UrlRenderer`, `DefaultIconResolver`.

### Models & security

- **FR-MDL-001 / FR-MDL-002**: Enums (`LinkFamily`, `MapAction`, …) and link/option models.
- **FR-SEC-004**: `UrlPolicy` validates schemes/hosts; `HtmlAttributePolicy` sanitizes rendered attributes.
- **FR-UTIL-001**: `PhoneNormalizer`, `UrlBuilder`.
- **FR-ERR-001**: Typed exceptions for invalid URLs, disabled providers, missing providers.

### Twig & i18n

- **FR-TWIG-001 / FR-TWIG-002**: Extension functions and UX Live components.
- **FR-VIEW-008**: Component Twig templates.
- **FR-I18N-004**: Seven locale translation files.
- **FR-DI-001 / FR-DI-002**: PHP service definitions and Twig path compiler pass.

---

## Success Criteria

- **SC-001**: 100% of production files in `src/` appear in [`code-inventory.md`](code-inventory.md) with requirement IDs (70/70 mapped).
- **SC-002**: Configuration keys in `docs/CONFIGURATION.md` match `Configuration.php`.
- **SC-003**: `composer qa` / `make release-check` pass in CI (PHPUnit, PHPStan, Vitest where applicable).
- **SC-004**: No Packagist-visible behavior change without spec, inventory, and test updates.

---

## Validation

| Check | Command |
| --- | --- |
| Full QA | `make release-check` or `composer qa` |
| Code inventory audit | `find src -type f ! -path '*/assets/dist/*' ! -name '*.test.ts' \| wc -l` |
| TS tests | `pnpm test` or `make test-ts` (when assets present) |

When changing behavior, update this spec, `code-inventory.md`, integrator docs, and tests.
