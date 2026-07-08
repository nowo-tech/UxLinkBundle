# Demo aggregate — Symfony 7 and 8 (UX Link Bundle).

DEMOS := symfony7 symfony8

.PHONY: help clean test-all verify-all release-check release-verify
.PHONY: $(foreach d,$(DEMOS),up-$d down-$d update-bundle-$d verify-$d)
.PHONY: up down update-bundle

help:
	@echo "UX Link Bundle — Demos (Symfony 7 & 8)"
	@echo "  make up DEMO=symfony7|symfony8   (or up-symfony7 / up-symfony8)"
	@echo "  make down DEMO=symfony7|symfony8"
	@echo "  make update-bundle DEMO=symfony7|symfony8"
	@echo "  make verify-all | test-all | release-check"
	@echo "  Ports: 8047 (symfony7), 8048 (symfony8)"

up-symfony7:
	@$(MAKE) -C symfony7 up

up-symfony8:
	@$(MAKE) -C symfony8 up

down-symfony7:
	@$(MAKE) -C symfony7 down

down-symfony8:
	@$(MAKE) -C symfony8 down

update-bundle-symfony7:
	@$(MAKE) -C symfony7 update-bundle

update-bundle-symfony8:
	@$(MAKE) -C symfony8 update-bundle

verify-symfony7:
	@$(MAKE) -C symfony7 verify

verify-symfony8:
	@$(MAKE) -C symfony8 verify

up:
	@if [ -n "$(DEMO)" ]; then \
		$(MAKE) up-$(DEMO); \
	else \
		$(MAKE) up-symfony7; \
	fi

down:
	@[ -n "$(DEMO)" ] || { echo "Usage: make down DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) down-$(DEMO)

update-bundle:
	@[ -n "$(DEMO)" ] || { echo "Usage: make update-bundle DEMO=symfony7|symfony8"; exit 1; }
	@$(MAKE) update-bundle-$(DEMO)

test-all:
	@$(MAKE) -C symfony7 test
	@$(MAKE) -C symfony8 test

verify-all:
	@$(MAKE) verify-symfony7
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
include $(BUNDLE_ROOT)/../.scripts/Makefile.demo-aggregate-update-deps.mk
