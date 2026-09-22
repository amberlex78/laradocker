# Docker architecture

The project uses one Compose project per Laravel application. The application, nginx, MariaDB, volumes, and Docker networks are isolated by `COMPOSE_PROJECT_NAME`.

```text
external Nginx (HTTPS)
        |
        | 127.0.0.1:8080
        v
project nginx:8080  --->  project app:9000 (PHP-FPM)
                              |
                              v
                       project mariadb:3306
```

## Images

- `docker/php/Dockerfile` builds the PHP-FPM application image with separate dependency and runtime stages.
- The `dev` target includes development Composer dependencies.
- The `prod` target excludes development dependencies and uses authoritative Composer autoloading.
- `docker/nginx/Dockerfile` builds frontend assets in a Node stage and copies the resulting `public/` directory into nginx.
- `docker/mariadb/Dockerfile` adds the project MariaDB configuration to the pinned MariaDB image.

The production images do not contain `.env`, `vendor` from the host, or `node_modules` from the host. Secrets are supplied at container startup through the production `.env` file.

## Networks and ports

- `frontend` connects nginx to PHP-FPM.
- `backend` is an internal network connecting PHP-FPM to MariaDB.
- PHP-FPM listens only on the Compose network at `app:9000`.
- MariaDB listens only on the Compose network in production.
- Production nginx is published only on `127.0.0.1:${PROD_HTTP_PORT}`.

The development overlay additionally publishes nginx, Vite, and MariaDB on localhost.

## Development mounts and production volumes

- `mariadb-data` persists the database.
- Development bind-mounts the project source, including `vendor/` and `node_modules/`, so host editors can index the installed dependencies.
- Development services run with the host user's UID/GID to prevent root-owned files in the working tree.
- Production does not bind-mount source code.

If the application later stores user uploads on the local filesystem, add a dedicated persistent volume for `storage/app` or move uploads to object storage. The current skeleton does not require that volume.

## Multiple projects on one VPS

Each project needs a distinct `COMPOSE_PROJECT_NAME` and production port:

```dotenv
COMPOSE_PROJECT_NAME=project-a
PROD_HTTP_PORT=8080
```

```dotenv
COMPOSE_PROJECT_NAME=project-b
PROD_HTTP_PORT=8081
```

The project names produce different networks, volumes, and image names. The external reverse proxy routes different hostnames to `127.0.0.1:8080` and `127.0.0.1:8081`.
