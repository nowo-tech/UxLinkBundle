# Changelog

All notable changes to this project are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
