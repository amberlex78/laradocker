SHELL := /bin/sh

PROJECT_NAME := $(or $(shell sed -n 's/^COMPOSE_PROJECT_NAME=//p' .env 2>/dev/null | tail -1),laravel-app)
DEV_HTTP_PORT := $(or $(shell sed -n 's/^DEV_HTTP_PORT=//p' .env 2>/dev/null | tail -1),8000)
PROD_HTTP_PORT := $(or $(shell sed -n 's/^PROD_HTTP_PORT=//p' .env 2>/dev/null | tail -1),8080)
DB_FORWARD_PORT := $(or $(shell sed -n 's/^DB_FORWARD_PORT=//p' .env 2>/dev/null | tail -1),3306)
VITE_FORWARD_PORT := $(or $(shell sed -n 's/^VITE_FORWARD_PORT=//p' .env 2>/dev/null | tail -1),5173)
VITE_PORT := $(or $(shell sed -n 's/^VITE_PORT=//p' .env 2>/dev/null | tail -1),5173)
VITE_HMR_PORT := $(or $(shell sed -n 's/^VITE_HMR_PORT=//p' .env 2>/dev/null | tail -1),5173)

COMPOSE := COMPOSE_PROJECT_NAME=$(PROJECT_NAME) DEV_HTTP_PORT=$(DEV_HTTP_PORT) PROD_HTTP_PORT=$(PROD_HTTP_PORT) DB_FORWARD_PORT=$(DB_FORWARD_PORT) VITE_FORWARD_PORT=$(VITE_FORWARD_PORT) VITE_PORT=$(VITE_PORT) VITE_HMR_PORT=$(VITE_HMR_PORT) docker compose --env-file .env
BASE_FILES := -f docker-compose.yml
DEV_FILES := $(BASE_FILES) -f docker-compose.dev.yml
PROD_FILES := $(BASE_FILES) -f docker-compose.prod.yml

.DEFAULT_GOAL := help

## -----------------------------------------------------------------------------
## GENERAL & CONFIGURATION
## -----------------------------------------------------------------------------

.PHONY: help ensure-env config-dev config-prod build \
        dev-up dev-build dev-down dev-restart dev-logs dev-status dev-clear dev-migrate \
        dev-seed dev-test dev-vite dev-worker dev-scheduler \
        prod-build prod-up prod-down prod-restart prod-logs prod-status \
        prod-migrate prod-optimize prod-deploy prod-worker prod-scheduler \
        tools-artisan tools-composer tools-npm tools-shell-php tools-shell-node \
        tools-shell-mariadb tools-pint tools-test tools-clean docker-stats

help: ## Show available commands
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make <target>\n\nTargets:\n"} \
		/^## [[:space:]]*[-—]+[[:space:]]*$$/ {next} \
		/^## [A-Z0-9][A-Z0-9 &()_-]*$$/ {printf "\n%s\n", substr($$0, 4); next} \
		/^[a-zA-Z0-9_.-]+:.*##/ {printf "  %-22s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

ensure-env:
	@test -f .env || (echo "Missing .env. Run: cp .env.example .env" && exit 1)

config-dev: ensure-env ## Validate the development Compose configuration
	$(COMPOSE) $(DEV_FILES) config --quiet

config-prod: ensure-env ## Validate the production Compose configuration
	$(COMPOSE) $(PROD_FILES) config --quiet

build: config-dev config-prod ## Validate both Compose configurations

## -----------------------------------------------------------------------------
## DEVELOPMENT (LOCAL)
## -----------------------------------------------------------------------------

dev-build: config-dev ## Build development images
	$(COMPOSE) $(DEV_FILES) build

dev-up: dev-build ## Install dependencies and start the development environment
	$(COMPOSE) $(DEV_FILES) --profile tools run --rm --no-deps composer install --no-interaction --prefer-dist
	$(COMPOSE) $(DEV_FILES) --profile dev up -d

dev-down: ensure-env ## Stop the development environment without deleting volumes
	$(COMPOSE) $(DEV_FILES) --profile dev --profile worker --profile scheduler --profile tools down --remove-orphans

dev-restart: ensure-env ## Restart development services
	$(COMPOSE) $(DEV_FILES) restart

dev-logs: ensure-env ## Follow development service logs
	$(COMPOSE) $(DEV_FILES) logs -f --tail=100

dev-status: ensure-env ## Show development container status
	$(COMPOSE) $(DEV_FILES) ps

dev-clear: ensure-env ## Clear Laravel optimization caches in development
	$(COMPOSE) $(DEV_FILES) run --rm --no-deps app php artisan optimize:clear

dev-migrate: ensure-env ## Run pending development migrations
	$(COMPOSE) $(DEV_FILES) run --rm app php artisan migrate --no-interaction

dev-seed: ensure-env ## Run development database seeders
	$(COMPOSE) $(DEV_FILES) run --rm app php artisan db:seed --no-interaction

dev-test: ensure-env ## Run the Laravel test suite in the development container
	$(COMPOSE) $(DEV_FILES) run --rm --no-deps app php artisan test --compact

dev-vite: ensure-env ## Start or restart the Vite HMR service
	$(COMPOSE) $(DEV_FILES) --profile dev up -d --force-recreate node

dev-worker: ensure-env ## Start the development queue worker profile
	$(COMPOSE) $(DEV_FILES) --profile worker up -d queue-worker

dev-scheduler: ensure-env ## Start the development scheduler profile
	$(COMPOSE) $(DEV_FILES) --profile scheduler up -d scheduler

## -----------------------------------------------------------------------------
## PRODUCTION (DEPLOYMENT)
## -----------------------------------------------------------------------------

prod-build: config-prod ## Build production images
	$(COMPOSE) $(PROD_FILES) build

prod-up: config-prod ## Start the production environment
	$(COMPOSE) $(PROD_FILES) up -d

prod-down: config-prod ## Stop the production environment without deleting volumes
	$(COMPOSE) $(PROD_FILES) --profile worker --profile scheduler down --remove-orphans

prod-restart: config-prod ## Restart production services
	$(COMPOSE) $(PROD_FILES) restart

prod-logs: config-prod ## Follow production service logs
	$(COMPOSE) $(PROD_FILES) logs -f --tail=100

prod-status: config-prod ## Show production status and health state
	$(COMPOSE) $(PROD_FILES) ps

prod-migrate: config-prod ## Run production migrations with --force
	$(COMPOSE) $(PROD_FILES) exec -T app php artisan migrate --force --no-interaction

prod-optimize: config-prod ## Cache production configuration, routes, and views
	$(COMPOSE) $(PROD_FILES) exec -T app sh -lc 'php artisan config:cache && php artisan route:cache && php artisan view:cache'

prod-deploy: prod-build prod-up ## Build, start, migrate, optimize, and smoke-test production
	$(MAKE) prod-migrate
	$(MAKE) prod-optimize
	$(COMPOSE) $(PROD_FILES) exec -T app php artisan about --only=environment
	curl --fail --silent --show-error --retry 10 --retry-delay 1 http://127.0.0.1:$(PROD_HTTP_PORT)/up >/dev/null
	@echo "Production smoke check passed on 127.0.0.1:$(PROD_HTTP_PORT)/up"

prod-worker: config-prod ## Start the production queue worker profile
	$(COMPOSE) $(PROD_FILES) --profile worker up -d queue-worker

prod-scheduler: config-prod ## Start the production scheduler profile
	$(COMPOSE) $(PROD_FILES) --profile scheduler up -d scheduler

## -----------------------------------------------------------------------------
## MONITORING
## -----------------------------------------------------------------------------

docker-stats: ensure-env ## Show live resource usage for this Compose project
	$(COMPOSE) $(DEV_FILES) stats

## -----------------------------------------------------------------------------
## LARAVEL TOOLS
## -----------------------------------------------------------------------------

tools-artisan: ensure-env ## Run an Artisan command, for example CMD="about"
	@test -n "$(CMD)" || (echo 'Usage: make tools-artisan CMD="about"' && exit 1)
	$(COMPOSE) $(DEV_FILES) run --rm app php artisan $(CMD)

tools-composer: ensure-env ## Run a Composer command, for example CMD="show"
	@test -n "$(CMD)" || (echo 'Usage: make tools-composer CMD="show"' && exit 1)
	$(COMPOSE) $(DEV_FILES) --profile tools run --rm --no-deps composer $(CMD)

tools-npm: ensure-env ## Run an npm command, for example CMD="run build"
	@test -n "$(CMD)" || (echo 'Usage: make tools-npm CMD="run build"' && exit 1)
	$(COMPOSE) $(DEV_FILES) --profile dev run --rm --no-deps node $(CMD)

tools-shell-php: ensure-env ## Open a shell in the development PHP container
	$(COMPOSE) $(DEV_FILES) run --rm app sh

tools-shell-node: ensure-env ## Open a shell in the development Node container
	$(COMPOSE) $(DEV_FILES) --profile dev run --rm --no-deps node sh

tools-shell-mariadb: ensure-env ## Open the MariaDB client
	$(COMPOSE) $(DEV_FILES) exec mariadb sh -lc 'mariadb -u"$${MARIADB_USER}" -p"$${MARIADB_PASSWORD}" "$${MARIADB_DATABASE}"'

tools-pint: ensure-env ## Run Laravel Pint on modified PHP files
	$(COMPOSE) $(DEV_FILES) run --rm --no-deps app vendor/bin/pint --dirty --format agent

tools-test: dev-test ## Alias for the full Laravel test suite

## -----------------------------------------------------------------------------
## CLEANUP
## -----------------------------------------------------------------------------

tools-clean: ensure-env ## Stop this project's containers and networks without deleting volumes
	$(COMPOSE) $(DEV_FILES) --profile dev --profile worker --profile scheduler --profile tools down --remove-orphans
	$(COMPOSE) $(PROD_FILES) --profile worker --profile scheduler down --remove-orphans
