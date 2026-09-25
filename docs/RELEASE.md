# Release

## Checklist

1. Ensure `make release-check` passes (bundle + demos).
2. Update `docs/CHANGELOG.md` (move entries from `Unreleased` to a versioned section).
3. Update `docs/UPGRADING.md` when integrators must change code or configuration.
4. Re-run / refresh `docs/FRANKENPHP-WORKER-AUDIT.md` when shared services change (FR-WORKER-002).
5. Commit on `main`.
6. Create an annotated tag `v*` (for example `v1.1.4`).
7. Push branch and **only the new tag** (avoid `git push --tags` when local old tags diverge):

```bash
git push origin main
git push origin v1.1.4
```

8. GitHub Actions `release.yml` creates the GitHub release from the tag.
9. Packagist picks up the new tag automatically.

## Tag example

```bash
git tag -a v1.1.4 -m "Release v1.1.4 — FrankenPHP worker scenario B hardening"
git push origin v1.1.4
```

See `docs/CHANGELOG.md` for version notes.

After creating the release commit and tag, run `make check-no-cursor-coauthor` again **before** `git push` (REQ-GIT-001). The release commit itself is not covered by an earlier `release-check` run.
