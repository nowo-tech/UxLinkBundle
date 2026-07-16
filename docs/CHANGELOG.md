# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
