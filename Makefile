SHELL := /bin/sh

# Dev containers write to bind-mounted project directories as the host user.
APP_UID ?= $(shell id -u)
APP_GID ?= $(shell id -g)

COMPOSE := \
	APP_UID=$(APP_UID) \
	APP_GID=$(APP_GID) \
	docker compose

DOCKER_DEV := $(COMPOSE) --env-file ./.env -f docker-compose.yml -f docker-compose.dev.yml
DOCKER_PROD := $(COMPOSE) --env-file ./.env.prod -f docker-compose.yml -f docker-compose.prod.yml

PHP_EXEC := $(DOCKER_DEV) exec app

.DEFAULT_GOAL := help
.SILENT:

## —————————————————————————————————————————————————————————————————————————————
## GENERAL & CONFIGURATION
## —————————————————————————————————————————————————————————————————————————————

.PHONY: help ensure-dev-env ensure-prod-env config-dev config-prod validate \
        build install up down restart logs status clear optimize tinker artisan migrate \
        migrate-fresh db-seed test pint vite env stats composer npm shell-php \
        shell-node shell-mariadb \
        prod-build prod-up prod-down prod-restart prod-logs prod-status \
        prod-migrate prod-optimize prod-deploy

help: ## Show available commands
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

ensure-dev-env:
	@test -f .env || (echo "Missing .env. Copy .env.example to .env first." && exit 1)

ensure-prod-env:
	@test -f .env.prod || (echo "Missing .env.prod. Copy .env.prod.example to .env.prod first." && exit 1)

config-dev: ensure-dev-env ## Validate the development Compose configuration
	$(DOCKER_DEV) config --quiet

config-prod: ensure-prod-env ## Validate the production Compose configuration
	$(DOCKER_PROD) config --quiet

validate: config-dev config-prod ## Validate both Compose configurations

## —————————————————————————————————————————————————————————————————————————————
## DEVELOPMENT (Local)
## —————————————————————————————————————————————————————————————————————————————

build: config-dev ## Build development images
	$(DOCKER_DEV) build

install: build ## Install development dependencies and generate APP_KEY if missing
	$(DOCKER_DEV) --profile tools run --rm --no-deps composer install --no-interaction --prefer-dist
	$(DOCKER_DEV) run --rm --no-deps app sh -lc 'if [ -z "$${APP_KEY:-}" ]; then php artisan key:generate --no-interaction; fi'
	$(DOCKER_DEV) run --rm --no-deps node npm ci --no-audit --no-fund

up: config-dev ## Start the development environment
	$(DOCKER_DEV) up -d

down: config-dev ## Stop the development environment without deleting volumes
	$(DOCKER_DEV) --profile tools down --remove-orphans

restart: config-dev ## Restart development services
	$(DOCKER_DEV) restart

logs: config-dev ## Follow development service logs
	$(DOCKER_DEV) logs -f --tail=100

status: config-dev ## Show development container status
	$(DOCKER_DEV) ps

clear: config-dev ## Clear Laravel optimization caches
	$(PHP_EXEC) php artisan optimize:clear

optimize: config-dev ## Clear and rebuild Laravel optimization caches
	$(PHP_EXEC) php artisan optimize:clear
	$(PHP_EXEC) php artisan optimize

tinker: config-dev ## Open Laravel Tinker
	$(PHP_EXEC) php artisan tinker

artisan: config-dev ## Run an Artisan command, for example CMD="about"
	@test -n "$(CMD)" || (echo 'Usage: make artisan CMD="about"' && exit 1)
	$(PHP_EXEC) php artisan $(CMD)

migrate: config-dev ## Run pending migrations
	$(PHP_EXEC) php artisan migrate --no-interaction

migrate-fresh: config-dev ## Recreate the database and run seeders (deletes all data)
	$(PHP_EXEC) php artisan migrate:fresh --seed --no-interaction


db-seed: config-dev ## Run database seeders
	$(PHP_EXEC) php artisan db:seed --no-interaction

test: config-dev ## Run the Laravel test suite
	$(PHP_EXEC) php artisan test --compact

pint: config-dev ## Run Laravel Pint
	$(PHP_EXEC) ./vendor/bin/pint

vite: config-dev ## Start or restart the Vite HMR service
	$(DOCKER_DEV) up -d --force-recreate node

env: config-dev ## Show environment variables for dev services
	@for service in app node mariadb; do \
	    echo ""; \
		echo "===== $$service ====="; \
		$(DOCKER_DEV) exec -T $$service env | sort; \
	done

composer: config-dev ## Run a Composer command, for example CMD="show"
	@test -n "$(CMD)" || (echo 'Usage: make composer CMD="show"' && exit 1)
	$(DOCKER_DEV) --profile tools run --rm --no-deps composer $(CMD)

npm: config-dev ## Run an npm command, for example CMD="run build"
	@test -n "$(CMD)" || (echo 'Usage: make npm CMD="run build"' && exit 1)
	$(DOCKER_DEV) run --rm --no-deps node $(CMD)

shell-php: config-dev ## Open a shell in the development PHP container
	$(PHP_EXEC) sh

shell-node: config-dev ## Open a shell in the development Node container
	$(DOCKER_DEV) exec node sh

shell-mariadb: config-dev ## Open the MariaDB client
	$(DOCKER_DEV) exec mariadb sh -lc 'mariadb -u"$${MARIADB_USER}" -p"$${MARIADB_PASSWORD}" "$${MARIADB_DATABASE}"'

## —————————————————————————————————————————————————————————————————————————————
## PRODUCTION (Deployment)
## —————————————————————————————————————————————————————————————————————————————

prod-build: config-prod ## Build production images
	$(DOCKER_PROD) build

prod-up: config-prod ## Start the production environment
	$(DOCKER_PROD) up -d

prod-down: config-prod ## Stop the production environment without deleting volumes
	$(DOCKER_PROD) down --remove-orphans

prod-restart: config-prod ## Restart production services
	$(DOCKER_PROD) restart

prod-logs: config-prod ## Follow production service logs
	$(DOCKER_PROD) logs -f --tail=100

prod-status: config-prod ## Show production status and health state
	$(DOCKER_PROD) ps

prod-migrate: config-prod ## Run production migrations with --force
	$(DOCKER_PROD) exec -T app php artisan migrate --force --no-interaction

prod-optimize: config-prod ## Cache production configuration, routes, and views
	$(DOCKER_PROD) exec -T app sh -lc 'php artisan config:cache && php artisan route:cache && php artisan view:cache'

prod-deploy: prod-build prod-up ## Build, start, migrate, optimize, and smoke-test production
	$(MAKE) prod-migrate
	$(MAKE) prod-optimize
	$(DOCKER_PROD) exec -T app php artisan about --only=environment
	@published_port=$$($(DOCKER_PROD) port nginx 8080 | sed 's/.*://'); \
	curl --fail --silent --show-error --retry 10 --retry-delay 1 "http://127.0.0.1:$${published_port}/up" >/dev/null; \
	echo "Production smoke check passed on 127.0.0.1:$${published_port}/up"

## —————————————————————————————————————————————————————————————————————————————
## MONITORING
## —————————————————————————————————————————————————————————————————————————————

stats: config-dev ## Show live resource usage for this Compose project
	$(DOCKER_DEV) stats
