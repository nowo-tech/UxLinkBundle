# FrankenPHP demos

Both demos use FrankenPHP (Caddy + PHP) on port 80 inside the container.

## Development

```bash
make -C demo/symfony7 up
# Demo started at: http://localhost:8047
```

Default runtime is **worker** (`FRANKENPHP_MODE=worker`). For classic PHP (hot-reload friendly), set `FRANKENPHP_MODE=classic` in `.env` and recreate the container — see below.

## Production-like

The image default `Caddyfile` enables FrankenPHP worker mode:

```text
php_server { worker /app/public/index.php 2 }
```

FrankenPHP worker mode: Supported (tested with worker enabled in the default Caddyfile). Keep `FRANKENPHP_MODE=worker` (the Compose default).

## Switching classic vs worker (`FRANKENPHP_MODE`)

Demos select the FrankenPHP runtime via **`FRANKENPHP_MODE`** in `.env` / `.env.example` (not a Dockerfile `ENV`):

| Value | Behaviour |
| --- | --- |
| **`worker`** (default) | Keep the worker Caddyfile (`php_server { worker ... }`) |
| **`classic`** | Entrypoint copies `Caddyfile.dev` (plain `php_server`, hot-reload friendly) |

Compose passes `FRANKENPHP_MODE=${FRANKENPHP_MODE:-worker}` into the PHP service. After changing `.env`, run `docker compose up -d` (or `make up`) so the container is **recreated** — a plain `restart` does not reload env. No image rebuild is required.

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Composer DNS errors in Docker/WSL | Compose includes `dns: 8.8.8.8` |
| Bundle changes not visible | `make update-bundle` in the demo |
| Port in use | Change `PORT` in `.env` |
