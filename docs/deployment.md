# Production deployment

Production images are built on the VPS from the checked-out Git revision. No registry is required.

## Prepare the server

Create a production `.env.prod` outside Git from the committed template:

```bash
cp .env.prod.example .env.prod
```

Then replace the placeholders with real production values:

```dotenv
COMPOSE_PROJECT_NAME=laravel-app
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:replace-with-a-real-key
APP_URL=https://app.example.com
PROD_HTTP_PORT=8080
DB_CONNECTION=mariadb
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=use-a-strong-password
DB_ROOT_PASSWORD=use-a-different-strong-root-password
```

The `.env.prod` file is excluded from the Docker build context. `APP_KEY` and database passwords are never baked into an image.

## Deploy

```bash
git checkout <version>
make ENV_FILE=.env.prod config-prod
make ENV_FILE=.env.prod prod-deploy
```

`prod-deploy` performs the following non-destructive sequence:

1. Builds the PHP, nginx, and MariaDB images.
2. Starts or updates the production services.
3. Runs `php artisan migrate --force`.
4. Caches configuration, routes, and views.
5. Checks Laravel `/up` through the local production port.

It does not run `migrate:fresh`, remove volumes, or delete database data.

Queue workers and the scheduler are opt-in:

```bash
make ENV_FILE=.env.prod prod-worker
make ENV_FILE=.env.prod prod-scheduler
```

If the application dispatches jobs or defines scheduled tasks, start the relevant profiles after deployment.

## External reverse proxy

The external Nginx terminates TLS and proxies the application domain to the project-local port:

```nginx
server {
    listen 443 ssl http2;
    server_name app.example.com;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

For a second Laravel project, use another hostname and port, for example `app-two.example.com` → `127.0.0.1:8081`. The two Compose projects must use different `COMPOSE_PROJECT_NAME` values and host ports.

## Backup and restore

Create a logical backup without publishing MariaDB externally:

```bash
docker compose --env-file .env.prod -f docker-compose.yml -f docker-compose.prod.yml exec -T mariadb \
    sh -lc 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' > backup.sql
```

Restore after reviewing the target database:

```bash
cat backup.sql | docker compose --env-file .env.prod -f docker-compose.yml -f docker-compose.prod.yml exec -T mariadb \
    sh -lc 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'
```

Use a password manager or a protected shell environment for backup credentials. Keep backups outside the project directory and test restoration periodically.

## Troubleshooting

- `make ENV_FILE=.env.prod config-prod`: checks Compose interpolation and service configuration.
- `make ENV_FILE=.env.prod prod-status`: shows service health.
- `make ENV_FILE=.env.prod prod-logs`: follows container logs.
- `docker compose ... exec app php artisan about --only=environment`: confirms the active Laravel environment.
- If `/up` fails, check MariaDB health first, then PHP-FPM logs, then nginx logs.
