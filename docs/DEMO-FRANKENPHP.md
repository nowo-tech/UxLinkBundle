# FrankenPHP demos

Both demos use FrankenPHP (Caddy + PHP) on port 80 inside the container.

## Development

```bash
make -C demo/symfony7 up
# Demo started at: http://localhost:8047
```

`APP_ENV=dev` copies `docker/frankenphp/Caddyfile.dev` (no worker mode).

## Production-like

Set `APP_ENV=prod` in the container environment. The production `Caddyfile` enables:

```text
php_server { worker /app/public/index.php 2 }
```

FrankenPHP worker mode: Supported (tested with worker enabled in production Caddyfile).

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Composer DNS errors in Docker/WSL | Compose includes `dns: 8.8.8.8` |
| Bundle changes not visible | `make update-bundle` in the demo |
| Port in use | Change `PORT` in `.env` |
