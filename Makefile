SHELL := /bin/bash

# UX Link Bundle — root Makefile (Docker PHP service).

COMPOSE_FILE := docker-compose.yml
# Prefer Compose V2 plugin (GitHub Actions / modern Docker Desktop); fall back to docker-compose V1 (REQ-MAKE-010).
COMPOSE_BIN := $(shell docker compose version >/dev/null 2>&1 && echo "docker compose" || echo "docker-compose")
COMPOSE     := $(COMPOSE_BIN) -f $(COMPOSE_FILE)
SERVICE_PHP := php

.PHONY: help up down down-dev build shell install test test-coverage test-coverage-100 coverage-check cs-check cs-fix qa clean release-check release-check-demos demo-smoke composer-sync rector rector-dry phpstan update validate validate-translations assets update-deps check-no-cursor-coauthor check-open-prs strip-cursor-coauthor-from-history setup-hooks

help:
	@echo "UX Link Bundle"
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@echo "  up / down / down-dev / build / shell / install"
	@echo "  test / test-coverage / coverage-check / cs-check / cs-fix / rector / phpstan / qa"
	@echo "  validate-translations / check-open-prs / demo-smoke / release-check"

build:
	$(COMPOSE) build --no-cache

up:
	$(COMPOSE) build
	$(COMPOSE) up -d
	$(COMPOSE) exec $(SERVICE_PHP) composer install --no-interaction

down:
	$(COMPOSE) down

down-dev: down
	@echo "Dev container stopped."

shell:
	$(COMPOSE) exec $(SERVICE_PHP) sh

install: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer install

ensure-up:
	@if ! $(COMPOSE) exec -T $(SERVICE_PHP) true 2>/dev/null; then \
		echo "Starting container..."; \
		$(COMPOSE) up -d; \
		sleep 3; \
		$(COMPOSE) exec -T $(SERVICE_PHP) composer install --no-interaction; \
	fi

test: ensure-up
	$(COMPOSE) exec $(SERVICE_PHP) composer test

test-coverage: ensure-up
	$(COMPOSE) exec $(SERVICE_PHP) composer test-coverage | tee coverage-php.txt
	@chmod +x .scripts/php-coverage-percent.sh
	./.scripts/php-coverage-percent.sh coverage-php.txt

test-coverage-100: test-coverage
	$(COMPOSE) exec -T $(SERVICE_PHP) php scripts/check-coverage.php coverage.xml --min-percent=100

coverage-check: test-coverage-100

cs-check: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer cs-check

cs-fix: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer cs-fix

rector: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer rector

rector-dry: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer rector-dry

phpstan: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer phpstan

qa: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer qa

update: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer update --no-interaction

validate: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer validate --strict

release-check: check-no-cursor-coauthor check-open-prs ensure-up composer-sync cs-check rector-dry phpstan validate-translations coverage-check release-check-demos

release-check-demos:
	@$(MAKE) -C demo release-check

validate-translations: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) php .scripts/validate-translation-keys.php


setup-hooks:
	@chmod +x .githooks/pre-commit 2>/dev/null || true
	@chmod +x .githooks/commit-msg 2>/dev/null || true
	@git config core.hooksPath .githooks
	@echo "✅ Git hooks installed (.githooks — includes commit-msg for REQ-GIT-001)."

# REQ-MAKE-008: update-deps (REQ-MAKE-008)
update-deps: ensure-up
	@$(COMPOSE) exec -T php composer update --no-interaction
	@$(MAKE) -C demo/symfony7 update-deps 2>/dev/null || $(MAKE) -C demo/symfony7 update-bundle
	@$(MAKE) -C demo/symfony8 update-deps 2>/dev/null || $(MAKE) -C demo/symfony8 update-bundle

composer-sync: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer validate --strict
	$(COMPOSE) exec -T $(SERVICE_PHP) composer update --no-install

clean: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) sh -c "rm -rf vendor .phpunit.cache coverage coverage.xml .php-cs-fixer.cache"

assets:
	@echo "No frontend assets in this bundle."
check-no-cursor-coauthor:
	@chmod +x .scripts/check-no-cursor-coauthor.sh
	@./.scripts/check-no-cursor-coauthor.sh HEAD

check-open-prs:
	@chmod +x .scripts/check-open-prs.sh
	@GH_REPO=nowo-tech/UxLinkBundle ./.scripts/check-open-prs.sh

demo-smoke:
	@if [ -f demo/Makefile ]; then $(MAKE) -C demo release-check; else echo "No demo/Makefile — skip demo-smoke"; fi

strip-cursor-coauthor-from-history:
	@chmod +x .scripts/strip-cursor-coauthor-from-history.sh
	@./.scripts/strip-cursor-coauthor-from-history.sh main
