# Local development

Requirements:

- Docker Engine with the Compose plugin;
- GNU Make.

The host does not need PHP, Composer, Node, or MariaDB.

## First run

```bash
cp .env.example .env
make dev-install
make dev-up
make dev-migrate
```

`make dev-install` builds the images, installs Composer and Node dependencies, and generates `APP_KEY` when it is empty. Run it after cloning the repository or when local dependencies have been removed.

If the project was already initialized, keep the existing `APP_KEY` and only update the Docker/MariaDB values in `.env`.

The application is available at [http://localhost:8000](http://localhost:8000), Vite HMR at `http://localhost:5173`, and MariaDB at `127.0.0.1:3306`.

## Common commands

```bash
make dev-up
make dev-down
make dev-logs
make dev-status
make dev-migrate
make dev-seed
make dev-test
make dev-vite
make tools-pint
make tools-artisan CMD="about"
make tools-composer CMD="show"
make tools-npm CMD="run build"
```

`make dev-up` only starts nginx, PHP-FPM, MariaDB, and Vite. Use `make dev-build` to rebuild images and `make dev-install` to reinstall project dependencies. Both `vendor/` and `node_modules/` remain visible to Zed and other host editors. The Makefile derives the host UID/GID automatically, so no permissions variables need to be edited manually. Queue workers and the scheduler are opt-in:

```bash
make dev-worker
make dev-scheduler
```

`make tools-clean` removes this project's containers and networks but does not delete the MariaDB volume. Do not use `docker compose down -v` unless deleting the local database is intentional.

## Running a second local project

Change these values in the second project's `.env`:

```dotenv
COMPOSE_PROJECT_NAME=second-laravel-app
DEV_HTTP_PORT=8001
VITE_FORWARD_PORT=5174
VITE_PORT=5174
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
```

Its containers and volumes will remain independent from the first project.

## Running the production image locally

Use a separate ignored env file and Compose project name so the production-like stack does not share containers or volumes with development:

```bash
cp .env.prod.example .env.prod
```

Set these local values in `.env.prod`:

```dotenv
COMPOSE_PROJECT_NAME=laravel-prod-local
APP_URL=http://localhost:8080
PROD_HTTP_PORT=8080
```

Start and stop it with the selected env file:

```bash
make ENV_FILE=.env.prod prod-deploy
make ENV_FILE=.env.prod prod-status
make ENV_FILE=.env.prod prod-down
```

The production-like stack uses built frontend assets, does not mount source code, and does not publish MariaDB to the host.
