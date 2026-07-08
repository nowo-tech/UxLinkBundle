# Symfony 7.4 demo — UX Link Bundle

.PHONY: help up down build install shell logs test test-coverage cache-clear update-bundle ensure-up verify

help:
	@echo "UX Link Bundle — Symfony 7.4 demo"
	@echo "  make up / down / build / install / shell / logs"
	@echo "  make test / verify / update-bundle"

up:
	@if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || true; fi
	docker-compose up -d
	@echo "Waiting for container to be ready..."
	@sleep 5
	@echo "Installing dependencies..."
	@docker-compose exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction
	@PORT=$$(grep "^PORT=" .env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	[ -z "$$PORT" ] && PORT=$$(grep "^PORT=" .env.example 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
	echo "Demo started at: http://localhost:$$PORT"

down:
	docker-compose down

restart: ensure-up
	docker-compose restart

build:
	docker-compose build --no-cache

ensure-up:
	@if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || true; fi
	@if ! docker-compose ps --status running --services 2>/dev/null | grep -qx php; then \
		echo "Starting demo container..."; \
		docker-compose up -d --build; \
		sleep 8; \
		docker-compose exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction; \
	fi

install: ensure-up
	docker-compose exec -T -e COMPOSER_MEMORY_LIMIT=-1 php composer install --no-interaction

shell: ensure-up
	docker-compose exec php sh

logs:
	docker-compose logs -f

test: ensure-up update-bundle
	docker-compose exec -T php rm -rf var/cache/test
	docker-compose exec -e APP_ENV=test php composer test

test-coverage: ensure-up update-bundle
	docker-compose exec php composer test-coverage

cache-clear: ensure-up
	docker-compose exec php php bin/console cache:clear

update-bundle: ensure-up
	docker-compose exec php composer update nowo-tech/ux-link-bundle --no-interaction
	docker-compose exec php php bin/console cache:clear --env=dev --no-warmup || true
	@echo "Bundle updated."

verify: ensure-up update-bundle
	@if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || true; fi
	@port=$$(grep '^PORT=' .env 2>/dev/null | cut -d= -f2 | tr -d '\r'); [ -z "$$port" ] && port=8047; \
	docker-compose up -d 2>/dev/null; sleep 8; \
	code=$$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:$$port 2>/dev/null || echo "000"); \
	if [ "$$code" = "200" ]; then echo "OK (HTTP $$code)"; else echo "Failed (HTTP $$code)"; exit 1; fi

# REQ-MAKE-008: update-deps (REQ-MAKE-008)
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/../..)
include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-update-deps.mk
