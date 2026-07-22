# Release

## Checklist

1. Ensure `make release-check` passes (bundle + demos).
2. Update `docs/CHANGELOG.md` (move entries from `Unreleased` to a versioned section).
3. Update `docs/UPGRADING.md` when integrators must change code or configuration.
4. Commit on `main`.
5. Create an annotated tag `v*` (for example `v1.0.0`).
6. Push branch and tags: `git push origin main --tags`.
7. GitHub Actions `release.yml` creates the GitHub release from the tag.
8. Packagist picks up the new tag automatically.

## Tag example

```bash
git tag -a v1.0.6 -m "Release v1.0.6 — FrankenPHP mode switch and CS Fixer hygiene"
git push origin v1.0.6
```

See `docs/CHANGELOG.md` for version notes.

After creating the release commit and tag, run `make check-no-cursor-coauthor` again **before** `git push` (REQ-GIT-001). The release commit itself is not covered by an earlier `release-check` run.
