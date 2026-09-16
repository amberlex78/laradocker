# Local development

Requirements:

- Docker Engine with the Compose plugin;
- GNU Make.

The host does not need PHP, Composer, Node, or MariaDB.

## First run

```bash
cp .env.example .env
make dev-up
make tools-artisan CMD="key:generate"
make dev-migrate
```

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

The first `make dev-up` builds the images, installs Composer dependencies into the named `vendor` volume, and starts nginx, PHP-FPM, MariaDB, and Vite. Queue workers and the scheduler are opt-in:

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
