# Security

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

## Reporting

See `.github/SECURITY.md` for disclosure policy.
