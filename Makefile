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

TEST_ENV := \
	-e APP_ENV=testing \
	-e APP_MAINTENANCE_DRIVER=file \
	-e BCRYPT_ROUNDS=4 \
	-e BROADCAST_CONNECTION=null \
	-e CACHE_STORE=array \
	-e DB_CONNECTION=sqlite \
	-e DB_DATABASE=:memory: \
	-e DB_URL= \
	-e MAIL_MAILER=array \
	-e QUEUE_CONNECTION=sync \
	-e SESSION_DRIVER=array \
	-e PULSE_ENABLED=false \
	-e TELESCOPE_ENABLED=false \
	-e NIGHTWATCH_ENABLED=false

TEST_PHP_EXEC := $(DOCKER_DEV) exec $(TEST_ENV) app

.DEFAULT_GOAL := help
.SILENT:

## —————————————————————————————————————————————————————————————————————————————
## GENERAL & CONFIGURATION
## —————————————————————————————————————————————————————————————————————————————

.PHONY: help config-dev config-prod validate \
        install build up down ps logs env \
        laravel-logs \
        stats clear tinker artisan migrate migrate-fresh db-seed \
        test pint phpstan composer npm vite shell-php shell-node shell-mariadb \
        prod-build prod-up prod-down \
        prod-ps prod-logs prod-laravel-logs prod-stats \
        prod-migrate prod-migrate-fresh prod-seed prod-optimize \
        prod-db-backup prod-db-restore deploy

help: ## Show available commands
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "} {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' \
		| sed -e 's/\[32m##/[33m/'

config-dev: ## Validate the development Compose configuration
	@test -f .env || (echo "Missing .env. Copy .env.example to .env first." && exit 1)
	$(DOCKER_DEV) config --quiet

config-prod: ## Validate the production Compose configuration
	@test -f .env.prod || (echo "Missing .env.prod. Copy .env.prod.example to .env.prod first." && exit 1)
	$(DOCKER_PROD) config --quiet

validate: config-dev config-prod ## Validate both Compose configurations

## —————————————————————————————————————————————————————————————————————————————
## DEVELOPMENT (Local)
## —————————————————————————————————————————————————————————————————————————————

## SETUP & LIFECYCLE

install: build ## Install development dependencies and generate APP_KEY if missing
	$(DOCKER_DEV) --profile tools run --rm --no-deps composer install --no-interaction --prefer-dist
	$(DOCKER_DEV) run --rm --no-deps app sh -lc 'if [ -z "$${APP_KEY:-}" ]; then php artisan key:generate --no-interaction; fi'
	$(DOCKER_DEV) run --rm --no-deps node npm ci --no-audit --no-fund

build: config-dev ## Build development images
	$(DOCKER_DEV) build

up: config-dev ## Start the development environment
	$(DOCKER_DEV) up -d

down: config-dev ## Stop the development environment without deleting volumes
	$(DOCKER_DEV) --profile tools down --remove-orphans

## OBSERVABILITY

ps: ## Show development container status
	$(DOCKER_DEV) ps

logs: ## Follow development service logs
	$(DOCKER_DEV) logs -f --tail=100

laravel-logs: ## Follow development Laravel logs via Pail
	$(PHP_EXEC) php artisan pail -v

env: ## Show environment variables for dev services
	@for service in app node mariadb; do \
	    echo ""; \
		echo "===== $$service ====="; \
		$(DOCKER_DEV) exec -T $$service env | sort; \
	done

stats: ## Show live resource usage for this Compose project
	$(DOCKER_DEV) stats

## LARAVEL

clear: ## Clear Laravel optimization caches
	$(PHP_EXEC) php artisan optimize:clear

tinker: ## Open Laravel Tinker
	$(PHP_EXEC) php artisan tinker

artisan: ## Run an Artisan command, for example CMD="about"
	@test -n "$(CMD)" || (echo 'Usage: make artisan CMD="about"' && exit 1)
	$(PHP_EXEC) php artisan $(CMD)

migrate: ## Run pending migrations
	$(PHP_EXEC) php artisan migrate --no-interaction

migrate-fresh: ## Recreate the database and run seeders (deletes all data)
	$(PHP_EXEC) php artisan migrate:fresh --seed --no-interaction

db-seed: ## Run database seeders
	$(PHP_EXEC) php artisan db:seed --no-interaction

## QUALITY

test: ## Run the Laravel test suite, optionally narrowed with CMD="tests/Feature/ExampleTest.php"
	$(TEST_PHP_EXEC) php artisan config:clear --ansi
	$(TEST_PHP_EXEC) php artisan test --compact $(CMD)

pint: ## Run Laravel Pint
	$(PHP_EXEC) ./vendor/bin/pint

phpstan: ## Run PHPStan static analysis
	$(PHP_EXEC) ./vendor/bin/phpstan analyse --memory-limit=1G

## DEPENDENCIES & FRONTEND

composer: ## Run a Composer command, for example CMD="show"
	@test -n "$(CMD)" || (echo 'Usage: make composer CMD="show"' && exit 1)
	$(DOCKER_DEV) --profile tools run --rm --no-deps composer $(CMD)

npm: ## Run an npm command, for example CMD="run build"
	@test -n "$(CMD)" || (echo 'Usage: make npm CMD="run build"' && exit 1)
	$(DOCKER_DEV) run --rm --no-deps --entrypoint npm node $(CMD)

vite: config-dev ## Start or restart the Vite HMR service
	$(DOCKER_DEV) up -d --force-recreate node

## SHELLS

shell-php: ## Open a shell in the development PHP container
	$(PHP_EXEC) sh

shell-node: ## Open a shell in the development Node container
	$(DOCKER_DEV) exec node sh

shell-mariadb: ## Open the MariaDB client
	$(DOCKER_DEV) exec mariadb sh -lc 'mariadb -u"$${MARIADB_USER}" -p"$${MARIADB_PASSWORD}" "$${MARIADB_DATABASE}"'

## —————————————————————————————————————————————————————————————————————————————
## PRODUCTION (Deployment)
## —————————————————————————————————————————————————————————————————————————————

## BUILD & LIFECYCLE

prod-build: config-prod ## Build production images
	$(DOCKER_PROD) build

prod-up: config-prod ## Start the production environment and wait for healthy services
	$(DOCKER_PROD) up -d --wait --wait-timeout 60

prod-down: config-prod ## Stop the production environment without deleting volumes
	$(DOCKER_PROD) down --remove-orphans

## OBSERVABILITY

prod-ps: ## Show production status and health state
	$(DOCKER_PROD) ps

prod-logs: ## Follow production service logs
	$(DOCKER_PROD) logs -f --tail=100

prod-laravel-logs: ## Follow Laravel application log
	$(DOCKER_PROD) exec -T app sh -lc 'tail -n 100 -F storage/logs/laravel.log'

prod-stats: ## Show live resource usage for the production Compose project
	$(DOCKER_PROD) stats

## LARAVEL

prod-migrate: ## Run production migrations with --force
	$(DOCKER_PROD) exec -T app php artisan migrate --force --no-interaction

prod-migrate-fresh: ## Recreate the production database and run seeders (deletes all data)
	$(DOCKER_PROD) exec -T app php artisan migrate:fresh --seed --force --no-interaction

prod-seed: ## Run production seeders explicitly
	$(DOCKER_PROD) exec -T app php artisan db:seed --force --no-interaction

prod-optimize: ## Cache production configuration, routes, and views
	$(DOCKER_PROD) exec -T app php artisan optimize

## DATABASE BACKUP

# BACKUP_DIR defaults to ~/backups/<COMPOSE_PROJECT_NAME> (outside the checkout).
# BACKUP_KEEP is the number of newest dumps retained after each backup.
prod-db-backup: config-prod ## Create a compressed MariaDB dump in ~/backups/<project> (BACKUP_DIR, BACKUP_KEEP=14)
	@set -eu; \
	project=$$(grep -E '^COMPOSE_PROJECT_NAME=' .env.prod | tail -n 1 | cut -d= -f2- | tr -d '"'); \
	dir="$${BACKUP_DIR:-$$HOME/backups/$${project:-laravel-app}}"; \
	keep="$${BACKUP_KEEP:-14}"; \
	umask 077; \
	mkdir -p "$$dir"; chmod 700 "$$dir"; \
	file="$$dir/database-$$(date -u +%Y%m%d-%H%M%SZ).sql"; \
	trap 'rm -f "$$file"' EXIT; \
	$(DOCKER_PROD) exec -T mariadb sh -c 'MYSQL_PWD="$$MARIADB_PASSWORD" exec mariadb-dump --single-transaction -u"$$MARIADB_USER" "$$MARIADB_DATABASE"' > "$$file"; \
	test -s "$$file" || { echo "Backup failed: dump is empty." >&2; exit 1; }; \
	tail -n 1 "$$file" | grep -q 'Dump completed' || { echo "Backup failed: dump is incomplete." >&2; exit 1; }; \
	gzip -9 "$$file"; \
	gzip -t "$$file.gz"; \
	trap - EXIT; \
	chmod 600 "$$file.gz"; \
	ls -1t "$$dir"/database-*.sql.gz | tail -n +$$((keep + 1)) | xargs -r rm -f --; \
	echo "Backup created: $$file.gz"

prod-db-restore: config-prod ## Restore MariaDB from a dump: make prod-db-restore FILE=path/to/database.sql.gz CONFIRM=yes
	@test -n "$(FILE)" || { echo "Usage: make prod-db-restore FILE=path/to/database.sql[.gz] CONFIRM=yes" >&2; exit 1; }
	@test -f "$(FILE)" || { echo "File not found: $(FILE)" >&2; exit 1; }
	@test "$(CONFIRM)" = "yes" || { echo "Restore overwrites the production database. Re-run with CONFIRM=yes." >&2; exit 1; }
	@echo "Creating a safety backup before restore..."
	$(MAKE) prod-db-backup BACKUP_KEEP=999999
	@case "$(FILE)" in \
		*.gz) gunzip -c "$(FILE)" ;; \
		*) cat "$(FILE)" ;; \
	esac | $(DOCKER_PROD) exec -T mariadb sh -c 'MYSQL_PWD="$$MARIADB_PASSWORD" exec mariadb -u"$$MARIADB_USER" "$$MARIADB_DATABASE"'
	@echo "Restore completed. Run make prod-migrate, make prod-optimize and make prod-ps."

## DEPLOYMENT

deploy: ## Pull, build, start, migrate, and optimize production
	@test -f .env.prod || (echo "Missing .env.prod. Copy .env.prod.example to .env.prod first." && exit 1)
	@test "$$(git branch --show-current)" = "main" || (echo "Deploy must run from the main branch." && exit 1)
	@test -z "$$(git status --porcelain)" || (echo "Working tree must be clean before deploy." && exit 1)
	git pull --ff-only origin main
	$(DOCKER_PROD) config --quiet
	$(DOCKER_PROD) build
	$(DOCKER_PROD) up -d --wait --wait-timeout 60
	$(MAKE) prod-migrate
	$(MAKE) prod-optimize
