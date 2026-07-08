## AI contribution guidelines (Nowo Symfony bundle)

Use this when suggesting code, tests, documentation, or CI changes for **UX Link Bundle**.

### Scope

- Symfony bundle published as `nowo-tech/ux-link-bundle` on Packagist.
- Respect **PHP >=8.2** and **Symfony 7.4 / 8.x** ranges in `composer.json`.
- Prefer **PHP 8 attributes** only. Do not introduce `doctrine/annotations`.

### Code

- Follow **PSR-12** and `.php-cs-fixer.dist.php`.
- Keep changes minimal; match patterns in `src/` and `tests/`.
- PHPDoc in **English** for non-trivial APIs.

### Tests and coverage

- Maintain **100% line coverage** on `src/` (`composer coverage-check`).
- Run `make cs-check`, `make phpstan`, and `make test` before proposing merges.

### Documentation

- User-facing docs are **English** under `docs/`; only `README.md` at repository root.
- Document Twig overrides under `templates/bundles/NowoUxLinkBundle/`.

### Demos

- Demos use **FrankenPHP** (`demo/symfony7`, `demo/symfony8`). See `docs/DEMO-FRANKENPHP.md`.
