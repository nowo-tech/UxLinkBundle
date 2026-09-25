# FrankenPHP worker mode audit (kernel not reset between requests)

| Field | Value |
|-------|-------|
| Package | `nowo-tech/ux-link-bundle` (`symfony-bundle`) |
| Audited revision | `v1.1.4` |
| Audit date | 2026-09-25 |
| Method | Manual review of every file under `src/` (services, providers, Twig extension and components, DI extension, compiler pass, `Resources/config/services.php`) + PHPStan FrankenPHP rulesets |
| **Verdict** | ✅ **PASS** — safe under scenario B (`reset_kernel` false / no `services_resetter`) |

## Execution model assumed

FrankenPHP worker mode boots the Symfony kernel once per worker and serves many requests with the same container. This audit assumes the **strict** variant: the kernel is **not** rebooted between requests, so every shared service, static property and PHP global survives from one request to the next. Two scenarios are evaluated:

- **A — kernel not rebooted, `services_resetter` still runs:** services tagged `kernel.reset` (or implementing `ResetInterface`) are reset between requests.
- **B — no reset at all:** nothing is reset; any per-request state kept in a service leaks into the next request.

A bundle that is safe under **B** is safe under **A** and under classic mode / PHP-FPM.

## Summary

| Area | Status | Notes |
|------|--------|-------|
| Mutable state in shared services | ✅ | `LinkProviderRegistry::$providers` is filled **only** in the constructor; `register()` is private |
| Static properties / `static` locals | ✅ | None mutable; util/policy helpers are pure static methods + constants |
| `ResetInterface` / `kernel.reset` coverage | ✅ N/A | Nothing needs a reset |
| Request / user / locale captured in services | ✅ | No `RequestStack` / `TokenStorage`; labels translated per call |
| Superglobals, `$_ENV`, `putenv`, `ini_set`, `setlocale`, timezone | ✅ | None used; config is compiled into the container |
| Doctrine / EntityManager | ✅ N/A | No persistence |
| Output, headers, `exit`, shutdown functions | ✅ | HTML returned as strings from Twig |
| Resources (files, sockets, cURL) held open | ✅ | None (`TwigPathsPass` only at compile time) |
| Memory growth across requests | ✅ | No caches or accumulating arrays in shared services |
| Blocking I/O and timeouts | ✅ N/A | Links are built as strings only |
| Twig components shared flag | ✅ | `#[AsTwigComponent]` + explicit `->share(false)` in `services.php` |
| PHPStan FrankenPHP rulesets | ✅ | `ruleset-classic.neon` + `ruleset-worker.neon` in `phpstan.neon.dist` |

## Services reviewed

| Service | Shared | Mutable state | Scenario A | Scenario B |
|---------|--------|---------------|------------|------------|
| `BundleConfiguration` | yes | none (`final readonly`) | ✅ | ✅ |
| `LinkProviderRegistry` | yes (public) | `$providers` — constructor-only | ✅ | ✅ |
| `LinkFactory` / `OptionsFactory` | yes | none | ✅ | ✅ |
| `HtmlLinkRenderer` / `UrlRenderer` / `DefaultIconResolver` | yes | none | ✅ | ✅ |
| 14 link providers under `src/Provider/` | yes | none | ✅ | ✅ |
| `UxLinkExtension` | yes | none | ✅ | ✅ |
| Twig components `UxLink`, `UxLinks`, `UxShareLinks`, `UxDownloadLink` | **no** (`share(false)` + UX) | `$link` / `$links` in `mount()` | ✅ | ✅ |

## Findings

No open findings. Former **W-01** (`LinkProviderRegistry::add()` public mutator) was resolved in **1.1.4** by making registration private and constructor-only.

## Usage recommendations in worker mode

- No special configuration or reset hook is needed for this bundle.
- Custom providers, icon resolvers (`IconResolverInterface`) or renderers (`LinkRendererInterface`) must stay **stateless**, or implement `ResetInterface`, to keep this verdict. Do not cache the current user, request or locale in them.
- Register custom providers as tagged services (`nowo_ux_link.provider` / `#[AsLinkProvider]`), never by mutating the registry at runtime.
- The demo in `demo/symfony8/` runs FrankenPHP in worker mode by default (`FRANKENPHP_MODE=worker`).

## Re-audit triggers

Re-run this audit when a change adds: properties to `LinkFactory`, `HtmlLinkRenderer`, the registry or any provider; a URL/label cache; an event listener or subscriber; injection of `RequestStack` / `TokenStorage`; a Twig component without `share(false)` / `#[AsTwigComponent]`; or any use of `$_SERVER` / `$_ENV` at runtime.
