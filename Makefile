SHELL := /bin/sh

ENV_FILE ?= .env

# Dev containers write to bind-mounted project directories as the host user.
APP_UID ?= $(shell id -u)
APP_GID ?= $(shell id -g)

COMPOSE := \
	APP_UID=$(APP_UID) \
	APP_GID=$(APP_GID) \
	RUNTIME_ENV_FILE=$(ENV_FILE) \
	docker compose --env-file $(ENV_FILE)
BASE_COMPOSE := $(COMPOSE) -f docker-compose.yml
DEV_COMPOSE := $(BASE_COMPOSE) -f docker-compose.dev.yml
PROD_COMPOSE := $(BASE_COMPOSE) -f docker-compose.prod.yml

.DEFAULT_GOAL := help
.SILENT:

## -----------------------------------------------------------------------------
## GENERAL & CONFIGURATION
## -----------------------------------------------------------------------------

.PHONY: help ensure-env config-dev config-prod validate \
        dev-build dev-install dev-up dev-down dev-restart dev-logs dev-status dev-clear dev-migrate \
        dev-seed dev-test dev-vite dev-env \
        prod-build prod-up prod-down prod-restart prod-logs prod-status \
        prod-migrate prod-optimize prod-deploy \
        tools-artisan tools-composer tools-npm tools-shell-php tools-shell-node \
        tools-shell-mariadb tools-pint tools-test tools-clean docker-stats

help: ## Show available commands
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make <target>\n\nTargets:\n"} \
		/^## [[:space:]]*[-—]+[[:space:]]*$$/ {next} \
		/^## [A-Z0-9][A-Z0-9 &()_-]*$$/ {printf "\n%s\n", substr($$0, 4); next} \
		/^[a-zA-Z0-9_.-]+:.*##/ {printf "  %-22s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

ensure-env:
	@test -f "$(ENV_FILE)" || (echo "Missing $(ENV_FILE). Copy the appropriate example env file first." && exit 1)

config-dev: ensure-env ## Validate the development Compose configuration
	$(DEV_COMPOSE) config --quiet

config-prod: ensure-env ## Validate the production Compose configuration
	$(PROD_COMPOSE) config --quiet

validate: config-dev config-prod ## Validate both Compose configurations

## -----------------------------------------------------------------------------
## DEVELOPMENT (LOCAL)
## -----------------------------------------------------------------------------

dev-build: config-dev ## Build development images
	$(DEV_COMPOSE) build

dev-install: dev-build ## Install development dependencies and generate APP_KEY if missing
	$(DEV_COMPOSE) --profile tools run --rm --no-deps composer install --no-interaction --prefer-dist
	$(DEV_COMPOSE) run --rm --no-deps app sh -lc 'if [ -z "$${APP_KEY:-}" ]; then php artisan key:generate --no-interaction; fi'
	$(DEV_COMPOSE) run --rm --no-deps node npm ci --no-audit --no-fund --cache /tmp/npm

dev-up: config-dev ## Start the development environment
	$(DEV_COMPOSE) up -d

dev-down: config-dev ## Stop the development environment without deleting volumes
	$(DEV_COMPOSE) --profile tools down --remove-orphans

dev-restart: config-dev ## Restart development services
	$(DEV_COMPOSE) restart

dev-logs: config-dev ## Follow development service logs
	$(DEV_COMPOSE) logs -f --tail=100

dev-status: config-dev ## Show development container status
	$(DEV_COMPOSE) ps

dev-clear: config-dev ## Clear Laravel optimization caches in development
	$(DEV_COMPOSE) run --rm --no-deps app php artisan optimize:clear

dev-migrate: config-dev ## Run pending development migrations
	$(DEV_COMPOSE) run --rm app php artisan migrate --no-interaction

dev-seed: config-dev ## Run development database seeders
	$(DEV_COMPOSE) run --rm app php artisan db:seed --no-interaction

dev-test: config-dev ## Run the Laravel test suite in the development container
	$(DEV_COMPOSE) run --rm --no-deps app php artisan test --compact

dev-vite: config-dev ## Start or restart the Vite HMR service
	$(DEV_COMPOSE) up -d --force-recreate node

dev-env: config-dev ## Show environment variables for dev services
	@for service in app node mariadb; do \
	    echo ""; \
		echo "===== $$service ====="; \
		$(DEV_COMPOSE) exec -T $$service env | sort; \
	done

## -----------------------------------------------------------------------------
## PRODUCTION (DEPLOYMENT)
## -----------------------------------------------------------------------------

prod-build: config-prod ## Build production images
	$(PROD_COMPOSE) build

prod-up: config-prod ## Start the production environment
	$(PROD_COMPOSE) up -d

prod-down: config-prod ## Stop the production environment without deleting volumes
	$(PROD_COMPOSE) down --remove-orphans

prod-restart: config-prod ## Restart production services
	$(PROD_COMPOSE) restart

prod-logs: config-prod ## Follow production service logs
	$(PROD_COMPOSE) logs -f --tail=100

prod-status: config-prod ## Show production status and health state
	$(PROD_COMPOSE) ps

prod-migrate: config-prod ## Run production migrations with --force
	$(PROD_COMPOSE) exec -T app php artisan migrate --force --no-interaction

prod-optimize: config-prod ## Cache production configuration, routes, and views
	$(PROD_COMPOSE) exec -T app sh -lc 'php artisan config:cache && php artisan route:cache && php artisan view:cache'

prod-deploy: prod-build prod-up ## Build, start, migrate, optimize, and smoke-test production
	$(MAKE) prod-migrate
	$(MAKE) prod-optimize
	$(PROD_COMPOSE) exec -T app php artisan about --only=environment
	@published_port=$$($(PROD_COMPOSE) port nginx 8080 | sed 's/.*://'); \
	curl --fail --silent --show-error --retry 10 --retry-delay 1 "http://127.0.0.1:$${published_port}/up" >/dev/null; \
	echo "Production smoke check passed on 127.0.0.1:$${published_port}/up"

## -----------------------------------------------------------------------------
## MONITORING
## -----------------------------------------------------------------------------

docker-stats: config-dev ## Show live resource usage for this Compose project
	$(DEV_COMPOSE) stats

## -----------------------------------------------------------------------------
## LARAVEL TOOLS
## -----------------------------------------------------------------------------

tools-artisan: config-dev ## Run an Artisan command, for example CMD="about"
	@test -n "$(CMD)" || (echo 'Usage: make tools-artisan CMD="about"' && exit 1)
	$(DEV_COMPOSE) run --rm app php artisan $(CMD)

tools-composer: config-dev ## Run a Composer command, for example CMD="show"
	@test -n "$(CMD)" || (echo 'Usage: make tools-composer CMD="show"' && exit 1)
	$(DEV_COMPOSE) --profile tools run --rm --no-deps composer $(CMD)

tools-npm: config-dev ## Run an npm command, for example CMD="run build"
	@test -n "$(CMD)" || (echo 'Usage: make tools-npm CMD="run build"' && exit 1)
	$(DEV_COMPOSE) run --rm --no-deps node $(CMD)

tools-shell-php: config-dev ## Open a shell in the development PHP container
	$(DEV_COMPOSE) run --rm app sh

tools-shell-node: config-dev ## Open a shell in the development Node container
	$(DEV_COMPOSE) run --rm --no-deps node sh

tools-shell-mariadb: config-dev ## Open the MariaDB client
	$(DEV_COMPOSE) exec mariadb sh -lc 'mariadb -u"$${MARIADB_USER}" -p"$${MARIADB_PASSWORD}" "$${MARIADB_DATABASE}"'

tools-pint: config-dev ## Run Laravel Pint on modified PHP files
	$(DEV_COMPOSE) run --rm --no-deps app vendor/bin/pint --dirty --format agent

tools-test: dev-test ## Alias for the full Laravel test suite
