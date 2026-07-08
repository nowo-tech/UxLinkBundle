# Security

## Table of contents

- [Scope](#scope)
- [Threat model](#threat-model)
- [Secrets](#secrets)
- [Release security checklist (12.4.1)](#release-security-checklist-1241)
- [Reporting](#reporting)

## Scope

This bundle **generates URLs and HTML links** locally. It does **not** call external APIs (WhatsApp, payment, meeting providers) unless you add custom providers that do.

## Threat model

| Input | Risk | Mitigation |
|-------|------|------------|
| User-supplied URLs | `javascript:`, `data:` XSS | `UrlPolicy` whitelist |
| HTML attributes | Event-handler injection | `HtmlAttributePolicy` whitelist |
| Share/contact text | Header injection in `mailto:` | RFC 3986 query encoding |
| Phone numbers | Malformed input | `PhoneNormalizer` + validation |

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

## Reporting

See [.github/SECURITY.md](../.github/SECURITY.md) for private disclosure.
