# Demo aggregate — Symfony 8 (UX Link Bundle).

DEMOS := symfony8

.PHONY: help clean test-all verify-all release-check release-verify
.PHONY: $(foreach d,$(DEMOS),up-$d down-$d update-bundle-$d verify-$d)
.PHONY: up down update-bundle

help:
	@echo "UX Link Bundle — Demo (Symfony 8)"
	@echo "  make up DEMO=symfony8   (or up-symfony8)"
	@echo "  make down DEMO=symfony8"
	@echo "  make update-bundle DEMO=symfony8"
	@echo "  make verify-all | test-all | release-check"
	@echo "  Port: 8048 (symfony8)"

up-symfony8:
	@$(MAKE) -C symfony8 up

down-symfony8:
	@$(MAKE) -C symfony8 down

update-bundle-symfony8:
	@$(MAKE) -C symfony8 update-bundle

verify-symfony8:
	@$(MAKE) -C symfony8 verify

up:
	@if [ -n "$(DEMO)" ]; then \
		$(MAKE) up-$(DEMO); \
	else \
		$(MAKE) up-symfony8; \
	fi

down:
	@[ -n "$(DEMO)" ] || { echo "Usage: make down DEMO=symfony8"; exit 1; }
	@$(MAKE) down-$(DEMO)

update-bundle:
	@[ -n "$(DEMO)" ] || { echo "Usage: make update-bundle DEMO=symfony8"; exit 1; }
	@$(MAKE) update-bundle-$(DEMO)

test-all:
	@$(MAKE) -C symfony8 test

verify-all:
	@$(MAKE) verify-symfony8

release-check: test-all release-verify
	@echo "All demos passed release-check."

release-verify:
	@for demo in $(DEMOS); do \
		port=$$(grep '^PORT=' $$demo/.env 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
		if [ -z "$$port" ]; then \
			port=$$(grep '^PORT=' $$demo/.env.example 2>/dev/null | cut -d= -f2 | tr -d '\r'); \
		fi; \
		echo "Release verify: $$demo (port $$port)..."; \
		$(MAKE) up-$$demo; \
		sleep 10; \
		code=$$(curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:$$port 2>/dev/null || echo "000"); \
		$(MAKE) down-$$demo 2>/dev/null || true; \
		if [ "$$code" != "200" ]; then echo "Demo $$demo healthcheck failed (HTTP $$code)"; exit 1; fi; \
		echo "OK $$demo (HTTP $$code)"; \
	done

clean:
	@rm -rf $(addsuffix /vendor,$(DEMOS)) $(addsuffix /var,$(DEMOS)) $(addsuffix /.phpunit.cache,$(DEMOS))
	@echo "Cleaned demos."

# REQ-MAKE-008: update-deps-all (REQ-MAKE-008)
BUNDLE_ROOT := $(abspath $(dir $(lastword $(MAKEFILE_LIST)))/..)
# Optional: monorepo helper absent on standalone GitHub Actions checkout (REQ-MAKE-009).
-include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-aggregate-update-deps.mk
