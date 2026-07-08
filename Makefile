# UX Link Bundle — root Makefile (Docker PHP service).

COMPOSE_FILE := docker-compose.yml
COMPOSE := docker-compose -f $(COMPOSE_FILE)
SERVICE_PHP := php

.PHONY: help up down down-dev build shell install test test-coverage cs-check cs-fix qa clean release-check release-check-demos composer-sync rector rector-dry phpstan update validate validate-translations assets update-deps

help:
	@echo "UX Link Bundle"
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@echo "  up / down / down-dev / build / shell / install"
	@echo "  test / test-coverage / cs-check / cs-fix / rector / phpstan / qa"
	@echo "  validate-translations / release-check / composer-sync / validate / clean"

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

release-check: ensure-up composer-sync cs-fix cs-check rector-dry phpstan test-coverage validate-translations release-check-demos

release-check-demos:
	@$(MAKE) -C demo release-check

validate-translations: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) php .scripts/validate-translation-keys.php

update-deps: ensure-up
	@docker-compose exec -T php composer update --no-interaction
	@$(MAKE) -C demo/symfony7 update-deps 2>/dev/null || $(MAKE) -C demo/symfony7 update-bundle
	@$(MAKE) -C demo/symfony8 update-deps 2>/dev/null || $(MAKE) -C demo/symfony8 update-bundle

composer-sync: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) composer validate --strict
	$(COMPOSE) exec -T $(SERVICE_PHP) composer update --no-install

clean: ensure-up
	$(COMPOSE) exec -T $(SERVICE_PHP) sh -c "rm -rf vendor .phpunit.cache coverage coverage.xml .php-cs-fixer.cache"

assets:
	@echo "No frontend assets in this bundle."
