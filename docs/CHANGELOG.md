# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Table of contents

- [[Unreleased]](#unreleased)
- [[1.2.0] - 2026-10-13](#120---2026-10-13)
- [[1.1.6] - 2026-10-09](#116---2026-10-09)
- [[1.1.5] - 2026-09-27](#115---2026-09-27)
- [[1.1.4] - 2026-09-25](#114---2026-09-25)
- [[1.1.3] - 2026-08-24](#113---2026-08-24)
- [[1.1.2] - 2026-08-19](#112---2026-08-19)
- [[1.1.1] - 2026-08-18](#111---2026-08-18)
- [[1.1.0] - 2026-08-04](#110---2026-08-04)
- [[1.0.7] - 2026-07-29](#107---2026-07-29)
- [[1.0.6] - 2026-07-22](#106---2026-07-22)
- [[1.0.5] - 2026-07-16](#105---2026-07-16)
- [[1.0.4] - 2026-07-13](#104---2026-07-13)
- [[1.0.3] - 2026-07-08](#103---2026-07-08)
- [[1.0.2] - 2026-07-08](#102---2026-07-08)
- [[1.0.1] - 2026-07-08](#101---2026-07-08)
- [[1.0.0] - 2026-07-08](#100---2026-07-08)

## [Unreleased]

### Security

- **Twig components apply `HtmlAttributePolicy`.** `ux-link-component`, `ux-links`, `ux-share-links` and
  `ux-download-link` iterated `LinkAttributes::toArray()` directly, so extra attributes such as `onclick` (from host
  code) reached the HTML. They render `LinkAttributes::safeArray()` now, the same filter as `HtmlLinkRenderer`.
- **E-mail contact links validate addresses.** A recipient such as `a@b.c?bcc=x@evil.test` added hidden recipients or
  a body to the draft. The recipient, `cc` and `bcc` must be valid e-mail addresses (`InvalidLinkOptionsException`
  otherwise).

### Changed

- Developer tooling: `make release-check` runs the full REQ-MAKE-002 chain in order (`ensure-up` first, then `check-no-cursor-coauthor`, `check-open-prs`, `check-twig-extra`, new `check-no-raw-html-form` (REQ-TWIG-005), `composer-sync`, `cs-fix`, `cs-check`, `rector-dry`, `phpstan`, `igor`, `validate-translations`, `coverage-check`, demo release-check).
- Dependencies refreshed (Composer and pnpm/npm lock files of the bundle and its demos, campaign 2026-10-19).
- `.php-cs-fixer.dist.php`: `new ParallelConfig(2)` (REQ-CS-001).
- Documentation: wave-1 REQ compliance — README documentation list order and Version information moved after the get-started sections, canonical bug report template, CONTRIBUTING Cursor Agent settings, TOCs for long docs, spec-driven development user stories / non-goals / validation / REQ table / Engram relationship, Spec Kit inventory refresh (71/71, ComponentTemplatesPass).

## [1.2.0] - 2026-10-13

### Fixed

- `templates.links`, `templates.share_links` and `templates.download_link` are now applied to the `UxLinks`, `UxShareLinks` and `UxDownloadLink` Twig components (a compiler pass replaces the template declared in `#[AsTwigComponent]`); they were previously stored as parameters only.
- `docs/CONFIGURATION.md` now documents the whole tree: `defaults.external_target_policy`, `families`, `providers.*` (`enabled`, `icon`, `label`, `priority`), `aliases` and `templates`.

### Security

- `UrlPolicy`: user-supplied relative URLs starting with `/\` (normalised by browsers to `//host`) or containing control characters (e.g. `/<TAB>/host`) are rejected (open redirect / off-site link).

### Changed

- Minimum Symfony version raised from 7.0 to 7.4 (`^7.4 || ^8.0`); the CI matrix no longer tests 7.0.

[1.2.0]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.2.0

## [1.1.6] - 2026-10-09

### Dependencies

- Dev: `igor-php/igor-php` constraint `^0.10.0` (Dependabot #8).
- Test fixture `config/reference.php` regenerated for Symfony 8.1.8.

[1.1.6]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.6

## [1.1.5] - 2026-09-27

### Added

- **REQ-CS-008:** `igor-php/igor-php` (require-dev only), root `igor.json`, Composer/`Makefile` `igor` target, and `release-check` wiring for FrankenPHP worker-state audit.

### Changed

- **Worker safety (Igor):** justified `// @igor-ignore` annotations and/or `ResetInterface` / request-scoped fixes so `make igor` passes on package `src/`.

[1.1.5]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.5

## [1.1.4] - 2026-09-25

### Changed

- **FrankenPHP worker (scenario B):** `LinkProviderRegistry` registration is constructor-only (`register()` private); Twig components explicitly `share(false)` in DI (FR-WORKER-001).
- **Docs / specs:** `docs/FRANKENPHP-WORKER-AUDIT.md` PASS verdict; FR-WORKER-001/002 in baseline spec; USAGE / DEMO / README / UPGRADING aligned.
- **Demos:** pin FrankenPHP demo Compose network to `10.248.48.0/24` to avoid exhausted default Docker address pools.

### Notes

- **No action** for integrators using tagged providers. Calling `LinkProviderRegistry::add()` at runtime is no longer possible (was undocumented).

[1.1.4]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.4

## [1.1.3] - 2026-08-24

### Changed

- **Docs:** PHP-FIG PSR evaluation (REQ-CS-007).

### Notes

- **No API or configuration changes** for integrators unless noted above.

[1.1.3]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.3

## [1.1.2] - 2026-08-19

### Security

- **CI:** run `composer audit --locked` after dependency install (REQ-SEC / P3).

## [1.1.1] - 2026-08-18

### Changed

- **Demos:** pin `nowo-tech/hot-reload-bundle` to `^1.4` with FrankenPHP Mercure/`hot_reload` (`dev`/`test` only).
- **Demos:** Symfony 8 only; Symfony 6/7 demo apps removed.

[1.1.1]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.1

## [1.1.0] - 2026-08-04

### Added
- **REQ-TWIG-004:** require `twig/extra-bundle` + `twig/string-extra`; `make check-twig-extra` in `release-check`; demos register `TwigExtraBundle`.
- **Twig-CS-Fixer:** `vincentlanglet/twig-cs-fixer`, `.twig-cs-fixer.php`, `composer twig:lint` / `twig:fix`.

[1.1.0]: https://github.com/nowo-tech/UxLinkBundle/releases/tag/v1.1.0

## [1.0.7] - 2026-07-29

### Added

- `make check-open-prs`, `coverage-check` / `test-coverage-100`, `demo-smoke`; `release-check` wires REQ-MAKE-002.
- `SYMFONY_DEPRECATIONS_HELPER=max[direct]=0` (REQ-SF-005).
- FrankenPHP Friendly banner (`docs/images/frankenphp-friendly.png`) — REQ-DOCS-017.
- `docs/SECURITY.md` 12.4.1 row for REQ-SEC-004 Pass (conditional).

### Changed

- Demo Symfony 8 image `dunglas/frankenphp:1-php8.5-alpine` (REQ-DEMO-010).
- Packagist homepage + GitHub About topics (REQ-DOCS-018).

## [1.0.6] - 2026-07-22

### Added

- FrankenPHP demos: `FRANKENPHP_MODE` (`worker`|`classic`) via `.env` / Compose, with dedicated `docker/entrypoint.sh` (REQ-DEMO-010).
- PHP-CS-Fixer: ordered imports, unused imports, and `fully_qualified_strict_types` with `import_symbols`.

### Changed

- `docs/DEMO-FRANKENPHP.md`: document classic vs worker switching and recreate-after-env guidance.
- Rector: skip `RemoveReturnTagIncompatibleWithNativeTypeRector` on `LinkProviderInterface` to keep narrowed PHPStan `@return`.
- CI: bump `actions/checkout` from 6 to 7.

### Fixed

- Integration test: import `Link` / `LinkFamily` instead of fully-qualified class names (CS Fixer alignment).

## [1.0.5] - 2026-07-16

### Added

- `CODE_OF_CONDUCT.md` (Contributor Covenant) and README / CONTRIBUTING links (REQ-DOCS-015).
- REQ-GIT-001 hygiene: `.githooks/commit-msg`, `.scripts/check-no-cursor-coauthor.sh`, `.scripts/strip-cursor-coauthor-from-history.sh`, Cursor rule `01-git-commits.mdc`, and `docs/GITHUB_CI.md`.
- CI job `git-hygiene` (full history) and Makefile targets `setup-hooks`, `check-no-cursor-coauthor`, `strip-cursor-coauthor-from-history` (wired into `release-check`).

### Changed

- `docs/RELEASE.md`: remind maintainers to re-run `make check-no-cursor-coauthor` after the release commit, before push.

## [1.0.4] - 2026-07-13

### Changed

- `.gitignore`: ignore `.cursor/sandbox.json` (machine-specific Cursor sandbox config; REQ-IDE-005).

## [1.0.3] - 2026-07-08

### Changed

- README: single-line canonical badges and Packagist/GitHub CTA blockquote (Nowo bundle standards REQ-DOCS-004, REQ-DOCS-009).
- Makefile: REQ-MAKE-008 traceability comment on root `update-deps` target.
- `.gitignore`: categorized patterns and `*.tar.gz` in the archives block (REQ-GITIGNORE-001).

## [1.0.2] - 2026-07-08

### Fixed

- Integration tests: add `twig_component.defaults` to the test kernel fixture (required by UX Twig Component 3.x on PHP 8.4+ in CI).

### Changed

- Makefile: fix `validate-translations` (remove duplicate target) and validate translation key parity across seven locales.
- Documentation: README coverage percentages and canonical badges; translation locales and overrides in `CONFIGURATION.md`; release security checklist (12.4.1) in `SECURITY.md`.
- `.github/SECURITY.md`: mark `1.x` as supported.

## [1.0.1] - 2026-07-08

### Fixed

- CI: run full `composer update` when no lock file is present (Composer 2 rejects partial updates without a lock file).
- CI: stop forcing `symfony/ux-twig-component:^3.0`, which requires PHP ≥ 8.4 and broke PHP 8.2/8.3 matrix jobs.

## [1.0.0] - 2026-07-08

### Added

- Initial release: Contact, Share, Map, and Download link families with extensible providers.
- Twig functions (`ux_link_url`, `ux_link`, `ux_links`) and UX Twig components (`UxLink`, `UxLinks`, `UxShareLinks`, `UxDownloadLink`).
- URL and HTML attribute safety via `UrlPolicy` and `HtmlAttributePolicy`.
- Custom providers through `LinkProviderInterface` and the `nowo_ux_link.provider` tag.
- Configuration tree under `nowo_ux_link` (defaults, aliases, enabled providers).
- Seven locale translation files.
- FrankenPHP demos for Symfony 7.4 (`8047`) and 8.1 (`8048`).
- Light internal phone normalization (ADR-007).
