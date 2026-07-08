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

## Tag example (1.0.0)

```bash
git tag -a v1.0.0 -m "Release v1.0.0 — initial public release"
git push origin v1.0.0
```

See `docs/CHANGELOG.md` for version notes.
