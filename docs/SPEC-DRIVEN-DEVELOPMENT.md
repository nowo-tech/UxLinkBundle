# Spec-driven development

In this repository, **spec-driven development** has three layers that stay in sync:

1. **GitHub Spec Kit baseline** — [`specs/001-baseline/`](../specs/001-baseline/) ([`spec.md`](../specs/001-baseline/spec.md), [`code-inventory.md`](../specs/001-baseline/code-inventory.md)), initialized with [GitHub Spec Kit](https://github.com/github/spec-kit) (`.specify/`, **Cursor Agent** skills in `.cursor/skills/speckit-*`). The inventory maps **100%** of production code in `src/`. **How to install, initialize, and use Spec Kit:** [`SPEC-KIT.md`](SPEC-KIT.md).
2. **Product behavior** — what **UxLinkBundle** guarantees to applications that integrate it (see [`USAGE.md`](USAGE.md), [`CONFIGURATION.md`](CONFIGURATION.md), [`INSTALLATION.md`](INSTALLATION.md)). **PHPUnit** and **PHPStan** (and **Vitest** when applicable) enforce contracts in CI where applicable.
3. **Traceability anchors** — stable **`REQ-*`** identifiers in Makefiles and demos (when present) so changes to scripts, ports, and demo workflows stay discoverable from issues and PRs.

There is no separate executable spec language (for example Gherkin); Spec Kit specs, tests, and static analysis are the mechanical proof alongside this document.

---

## Table of contents

- [User stories](#user-stories)
- [Product vision](#product-vision)
- [v1.0 scope](#v10-scope)
- [Key ADRs (approved)](#key-adrs-approved)
- [Traceability](#traceability)
- [Explicit non-goals](#explicit-non-goals)
- [Validating the functional spec](#validating-the-functional-spec)
- [Requirement identifiers (`REQ-*`)](#requirement-identifiers-req-)
- [Suggested workflow for contributors](#suggested-workflow-for-contributors)
- [GitHub Spec Kit (summary)](#github-spec-kit-summary)
- [Relationship to Engram](#relationship-to-engram)
- [See also](#see-also)

## User stories

| ID | Story | Integrator docs |
| --- | --- | --- |
| US-01 | As an integrator, I resolve contact / share / map / download links by provider id and options, then render HTML or URLs in Twig. | [USAGE — PHP](USAGE.md#php), [USAGE — Twig functions](USAGE.md#twig-functions) |
| US-02 | As an integrator, I register my own providers with `#[AsLinkProvider]` without forking the bundle. | [USAGE — Custom providers](USAGE.md#custom-providers) |
| US-03 | As a security-conscious integrator, I rely on `UrlPolicy` and `HtmlAttributePolicy` to block dangerous schemes and attributes. | [SECURITY.md](SECURITY.md) |
| US-04 | As a frontend developer, I embed `<twig:ux-link>`, link lists, share grids and download components. | [USAGE — Twig Components](USAGE.md#twig-components) |

## Product vision

Safe, extensible link generation for Symfony — inspired by Symfony UX CalendarLink, published as `nowo-tech/ux-link-bundle`.

## v1.0 scope

| Family | Providers |
|--------|-----------|
| Contact | whatsapp, email, telephone, sms |
| Share | linkedin, x, whatsapp, telegram, email |
| Map | google_maps, apple_maps, waze, openstreetmap |
| Download | download |

## Key ADRs (approved)

| ADR | Decision |
|-----|----------|
| ADR-007 | Light internal `PhoneNormalizer` (no libphonenumber) |
| ADR-008 | PHP `>=8.2`, Symfony `^7.4 \|\| ^8.0` |
| ADR-003 | Single `LinkProviderInterface` + family DTOs |
| ADR-006 | No domain events in v1.0 |

## Traceability

Integration tests in `tests/Integration/AllProvidersIntegrationTest.php` cover each bundled provider.

## Explicit non-goals

- Full international phone number validation (light internal `PhoneNormalizer` only, ADR-007).
- Domain events around link generation (ADR-006).
- Tracking, analytics or URL shortening of generated links.
- The demo under `demo/` is illustrative, not part of the Packagist API.

## Validating the functional spec

- **`make qa`** (Docker) or **`composer qa`**: coding standard, PHPStan (FrankenPHP classic + worker rulesets), PHPUnit.
- **`make release-check`**: full pre-release gate (includes `check-no-cursor-coauthor`).
- `tests/Integration/AllProvidersIntegrationTest.php` covers each bundled provider; behavior changes require tests under `tests/` and an update of [`specs/001-baseline/spec.md`](../specs/001-baseline/spec.md).

## Requirement identifiers (`REQ-*`)

| ID | Location | Meaning |
| --- | --- | --- |
| REQ-GIT-001 | `make check-no-cursor-coauthor`, `.githooks/commit-msg`, CI `git-hygiene` | No AI co-author trailers in history |
| REQ-CS-008 | Root `Makefile` (`igor`) | Igor worker-state audit |
| REQ-MAKE-008 | `demo/Makefile`, `demo/symfony8/Makefile` (`update-deps*`) | Update Composer dependencies in demos |
| REQ-MAKE-009 | `demo/Makefile`, `demo/symfony8/Makefile` | Optional monorepo helper absent on standalone checkouts |
| REQ-MAKE-010 | Root `Makefile` | Prefer Compose V2, fall back to V1 |

When scripted behavior changes, update the comment next to the existing `REQ-*` or add a new ID and cite it from PRs/issues.

## Suggested workflow for contributors

1. **Clarify behavior** in an issue or draft PR: acceptance criteria for the **product** and, if relevant, **Makefiles/demos** (`REQ-*`).
2. **Implement** with tests and static analysis.
3. **Anchor scripts and demos** when dev UX changes: add or adjust `REQ-*` comments and the requirement table.
4. **Ship integrator docs** when behavior or configuration changes: [`USAGE.md`](USAGE.md), [`CONFIGURATION.md`](CONFIGURATION.md), [`CHANGELOG.md`](CHANGELOG.md), and [`UPGRADING.md`](UPGRADING.md) when consumers must change code or config.
5. **Keep Spec Kit artifacts in sync** when production code under `src/` changes:
   - Update [`specs/001-baseline/spec.md`](../specs/001-baseline/spec.md) and [`code-inventory.md`](../specs/001-baseline/code-inventory.md).
   - Follow the maintainer checklist in [`SPEC-KIT.md`](SPEC-KIT.md).
   - For **new features**, use Cursor Agent skills (`/speckit-specify`, `/speckit-plan`, `/speckit-tasks`) as documented in SPEC-KIT.

---

## GitHub Spec Kit (summary)

This repository uses [GitHub Spec Kit](https://github.com/github/spec-kit) with **Cursor Agent** (`cursor-agent` integration).

| Artifact | Path |
| --- | --- |
| **Operator manual** (install, init, usage) | [`SPEC-KIT.md`](SPEC-KIT.md) |
| Baseline spec | [`specs/001-baseline/spec.md`](../specs/001-baseline/spec.md) |
| Code inventory (100%) | [`specs/001-baseline/code-inventory.md`](../specs/001-baseline/code-inventory.md) |
| Constitution | [`.specify/memory/constitution.md`](../.specify/memory/constitution.md) |
| Cursor Agent skills | [`.cursor/skills/`](../.cursor/skills/) (`speckit-*`) |

**Quick start (maintainers):**

```bash
# Install Specify CLI (once per machine) — see SPEC-KIT.md
specify init --here --force --integration cursor-agent --script sh
specify integration list   # Cursor → installed (default)
```

In Cursor Agent, start a new feature with `/speckit-specify <description>`. For day-to-day tooling details, skills reference, folder layout, and troubleshooting, read **[`SPEC-KIT.md`](SPEC-KIT.md)**.

## Relationship to Engram

[ENGRAM.md](ENGRAM.md) documents the org-wide checklist plane (Engram MCP memory, documentation hygiene). It does **not** replace this document: the functional contract (user stories, scope, validation, `REQ-*` anchors) lives here and in `specs/001-baseline/`.

## See also

- [SPEC-KIT.md](SPEC-KIT.md) · [`specs/001-baseline/spec.md`](../specs/001-baseline/spec.md)
- [USAGE.md](USAGE.md) · [CONFIGURATION.md](CONFIGURATION.md) · [CONTRIBUTING.md](CONTRIBUTING.md) · [RELEASE.md](RELEASE.md)
