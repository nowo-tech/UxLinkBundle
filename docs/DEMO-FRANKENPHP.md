# FrankenPHP demos

**REQ-DEMO-001:** FrankenPHP demos must install **Nowo Twig Inspector** and **Nowo Hot Reload** together (`nowo-tech/twig-inspector-bundle` + `nowo-tech/hot-reload-bundle` in `require-dev`). Caddyfile: Mercure + `hot_reload` (and `worker { file …; watch }` in worker mode). Do not enable Hot Reload in production.

Both demos use FrankenPHP (Caddy + PHP) on port 80 inside the container.

## Table of contents

- [Development](#development)
- [Production-like](#production-like)
- [Switching classic vs worker (`FRANKENPHP_MODE`)](#switching-classic-vs-worker-frankenphp_mode)
- [Demo smoke (REQ-TEST-011)](#demo-smoke-req-test-011)
- [Troubleshooting](#troubleshooting)

## Development

```bash
make -C demo/symfony8 up
# Demo started at: http://localhost:8048
```

Default runtime is **worker** (`FRANKENPHP_MODE=worker`). For classic PHP (hot-reload friendly), set `FRANKENPHP_MODE=classic` in `.env` and recreate the container — see below.

## Production-like

The image default `Caddyfile` enables FrankenPHP worker mode:

```text
php_server { worker /app/public/index.php 2 }
```

FrankenPHP worker mode: Supported under **scenario B** (kernel not reset between requests). See [FRANKENPHP-WORKER-AUDIT.md](FRANKENPHP-WORKER-AUDIT.md). Keep `FRANKENPHP_MODE=worker` (the Compose default).

## Switching classic vs worker (`FRANKENPHP_MODE`)

Demos select the FrankenPHP runtime via **`FRANKENPHP_MODE`** in `.env` / `.env.example` (not a Dockerfile `ENV`):

| Value | Behaviour |
| --- | --- |
| **`worker`** (default) | Keep the worker Caddyfile (`php_server { worker ... }`) |
| **`classic`** | Entrypoint copies `Caddyfile.dev` (plain `php_server`, hot-reload friendly) |

Compose passes `FRANKENPHP_MODE=${FRANKENPHP_MODE:-worker}` into the PHP service. After changing `.env`, run `docker compose up -d` (or `make up`) so the container is **recreated** — a plain `restart` does not reload env. No image rebuild is required.

## Demo smoke (REQ-TEST-011)

`make demo-smoke` (root) runs `make -C demo release-verify`: it starts each demo (`make -C demo/symfony8 up`, which copies `.env.example` to `.env` when missing and runs `composer install`), requests `http://127.0.0.1:<PORT>/` (`PORT` from `.env`, default **8048**), stops the demo and fails unless the answer is **HTTP 200**.

The [`Demo smoke`](../.github/workflows/demo-smoke.yml) workflow runs the same target weekly (Monday 07:00 UTC), on `v*` tags and on demand (`workflow_dispatch`). The full demo gate (demo PHPUnit + smoke) stays in `make release-check` through `release-check-demos`.

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Composer DNS errors in Docker/WSL | Compose includes `dns: 8.8.8.8` |
| Bundle changes not visible | `make update-bundle` in the demo |
| Port in use | Change `PORT` in `.env` |
