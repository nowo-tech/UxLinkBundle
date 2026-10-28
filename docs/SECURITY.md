# Security

## Table of contents

- [Scope](#scope)
- [Attack surface](#attack-surface)
- [Threat model](#threat-model)
- [Logging](#logging)
- [Dependencies](#dependencies)
- [Secrets](#secrets)
- [Release security checklist (12.4.1)](#release-security-checklist-1241)
- [Reporting](#reporting)

## Scope

This bundle **generates URLs and HTML links** locally. It does **not** call external APIs (WhatsApp, payment, meeting providers) unless you add custom providers that do.

## Attack surface

- **Twig functions** `ux_link()` / `ux_links()` and the Twig components `UxLink`, `UxLinks`, `UxShareLinks`,
  `UxDownloadLink`: they print HTML (`is_safe html`), so every URL and attribute goes through the policies below.
- **Link options** passed by host code or forwarded from user input: URLs, share text, recipients (`email`, `phone`),
  coordinates, file names and extra HTML attributes.
- **Bundle configuration** (`nowo_ux_link.*`): providers, labels, icons, templates, aliases.
- **Custom providers** (`#[AsLinkProvider]`) registered by the host.
- No HTTP routes, controllers, forms, storage or outbound requests: the bundle only builds strings.

## Threat model

| Input | Risk | Mitigation |
|-------|------|------------|
| User-supplied URLs | `javascript:`, `data:` XSS | `UrlPolicy` whitelist |
| HTML attributes | Event-handler injection | `HtmlAttributePolicy` whitelist, applied by the renderer and every component template (`LinkAttributes::safeArray()`) |
| Share/contact text | Header injection in `mailto:` | RFC 3986 query encoding; recipient, `cc` and `bcc` validated with `FILTER_VALIDATE_EMAIL` |
| Phone numbers | Malformed input | `PhoneNormalizer` + validation |

## Logging

The bundle does not log: it has no logger dependency and records nothing about the links it builds. Hosts that log
generated links should treat recipients, phone numbers and share text as personal data.

## Dependencies

Runtime dependencies are Symfony components (`framework-bundle`, `twig-bundle`, `translation`, `config`,
`dependency-injection`, `http-kernel`, `yaml`), `symfony/ux-twig-component` for the components and Twig Extra. `composer audit` runs on the bundle and the demos before every
release; Dependabot alerts are triaged and security fixes ship as patch releases listed under *Security* in the
[CHANGELOG](CHANGELOG.md).

## Secrets

Do not put API keys or payment credentials in bundle configuration. Payment and meeting features in future versions will remain link-only unless explicitly documented otherwise.

## Release security checklist (12.4.1)

Before each release, confirm:

| Item | Status |
| --- | --- |
| `docs/SECURITY.md` and `.github/SECURITY.md` up to date | ☐ |
| `.env` listed in `.gitignore`; no secrets in repo | ☐ |
| Default configuration contains no secrets | ☐ |
| User-supplied URLs and options validated (`UrlPolicy`, option models) | ☐ |
| Rendered HTML attributes escaped/sanitized (`HtmlAttributePolicy`, Twig) | ☐ |
| `composer audit` run on bundle and demos | ☐ |
| Logs do not dump user PII or secrets | ☐ |
| No custom cryptography in the bundle (link generation only) | ☐ |
| Custom providers documented; apps review third-party URL schemes | ☐ |
| No server-side HTTP calls in bundled providers (SSRF surface limited to client navigation) | ☐ |
| **AI security audit (REQ-SEC-004)** | **Pass (good)** / **Low** (re-audit 2026-09-28; components filtered by `HtmlAttributePolicy` since 1.2.1) — recorded in the Nowo monorepo `BUNDLES_SECURITY_ANALYSIS.md` |

## Reporting

See [.github/SECURITY.md](../.github/SECURITY.md) for private disclosure.
