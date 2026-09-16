# Laravel Docker Application

Laravel-застосунок, підготовлений для роботи повністю в Docker.

Один і той самий репозиторій підтримує два сценарії:

- локальна розробка з bind-mount коду, Vite HMR і доступною локально MariaDB;
- production на VPS, де образи будуються безпосередньо з Git checkout, а HTTPS завершується зовнішнім reverse proxy.

## Стек

| Компонент | Версія / образ | Призначення |
| --- | --- | --- |
| Laravel | 13.x | Application framework |
| PHP-FPM | `php:8.5-fpm-bookworm` | Виконання PHP-коду |
| Nginx | `nginx:1.30.4-alpine` | Web server і віддача frontend assets |
| MariaDB | `mariadb:11.8.9` | Основна база даних |
| Composer | `composer:2.9.8` | PHP-залежності |
| Node.js | `node:24-bookworm-slim` | Vite та frontend dependencies |

Redis на першому етапі не використовується. Session і cache працюють через MariaDB. Queue driver також налаштований на MariaDB, але queue worker наразі не входить до Compose-оточення, тому jobs не обробляються автоматично.

## Швидкий старт

Потрібні тільки:

- Docker Engine з Compose plugin;
- GNU Make.

PHP, Composer, Node.js і MariaDB на host-машині не потрібні.

```bash
cp .env.example .env
make dev-install
make dev-up
make dev-migrate
```

`make dev-install` потрібен тільки після clone або після видалення локальних залежностей. Він збирає образи, встановлює Composer і Node-залежності та генерує `APP_KEY`, якщо він порожній.

Після запуску:

- Laravel: <http://localhost:8000>;
- health endpoint: <http://localhost:8000/up>;
- Vite HMR: <http://localhost:5173>;
- MariaDB: `127.0.0.1:3306`.

`make dev-up` лише запускає PHP-FPM, Nginx, MariaDB та Vite. Для повторної збірки образів використовуй `make dev-build`, а для оновлення залежностей — `make dev-install`.

`vendor/`, `node_modules/`, `public/build/`, `public/hot` і Laravel cache створюються від UID/GID поточного користувача хоста. Тому Zed та інші редактори бачать залежності безпосередньо у робочому дереві, як у звичайній локальній Laravel-розробці.

## Як працює Docker-архітектура

Compose розділений на три файли:

- `docker-compose.yml` — спільні сервіси, мережі, volumes, healthchecks;
- `docker-compose.dev.yml` — локальні bind-mount-и та host-порти;
- `docker-compose.prod.yml` — self-contained production images без bind-mount source code.

Основний потік запитів:

```text
Browser
  │
  ├── dev:  http://localhost:8000
  └── prod: external Nginx → 127.0.0.1:8080
                              │
                              ▼
                        project nginx:8080
                              │
                              ▼
                        project app:9000
                              │
                              ▼
                        project mariadb:3306
```

Сервіси підключені до ізольованих мереж:

- `frontend` — Nginx ↔ PHP-FPM;
- `backend` — PHP-FPM ↔ MariaDB, мережа `internal`.

PHP runtime працює від non-root користувача `app`. Production MariaDB не публікується на host-порт. Дані MariaDB зберігаються у named volume `mariadb-data`.

## Як запустити ще один локальний проєкт

Щоб запустити ще один незалежний clone цього Laravel-проєкту, склонuj його в окрему директорію та створи власний `.env`:

```bash
git clone <URL-РЕПОЗИТОРІЮ> second-laravel-app
cd second-laravel-app
cp .env.example .env
```

У `.env` другого проєкту задай інше ім’я Compose-проєкту та вільні host-порти:

```dotenv
COMPOSE_PROJECT_NAME=second-laravel-app
APP_NAME=Second Laravel App
APP_URL=http://localhost:8001

DEV_HTTP_PORT=8001
VITE_FORWARD_PORT=5174
VITE_PORT=5173
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
```

Після цього виконай стандартний перший запуск:

```bash
make dev-install
make dev-up
make dev-migrate
```

`COMPOSE_PROJECT_NAME` ізолює контейнери, мережі, image names і MariaDB volume другого проєкту від першого. `DB_HOST`, `DB_PORT`, `DB_DATABASE` та внутрішній Vite-порт змінювати не потрібно: вони працюють усередині окремого Compose-оточення.

Для другого clone будуть доступні такі host-порти:

- Laravel: `http://localhost:8001`;
- Vite HMR: `http://localhost:5174`;
- MariaDB: `127.0.0.1:3307`.

Не копіюй `.env` з першого проєкту: новий clone має отримати власний `APP_KEY`, а база даних буде порожньою та незалежною. Якщо потрібно перенести дані, зроби окремий backup і restore MariaDB.

## Основні команди

Подивитися всі доступні команди:

```bash
make
```

### Розробка

```bash
make dev-up       # запустити dev-оточення
make dev-down     # зупинити контейнери без видалення volumes
make dev-status   # перевірити статус сервісів
make dev-logs     # переглядати логи
make dev-restart  # перезапустити сервіси
```

### Laravel і база даних

```bash
make dev-migrate
make dev-seed
make dev-clear
make tools-artisan CMD="about"
make tools-artisan CMD="route:list"
```

### Тести та якість коду

```bash
make dev-test
make tools-pint
```

### Composer, npm і shell

```bash
make tools-composer CMD="show"
make tools-npm CMD="run build"
make tools-shell-php
make tools-shell-node
make tools-shell-mariadb
```

### Vite

Vite запускається разом із `make dev-up`. Якщо його потрібно перезапустити:

```bash
make dev-vite
```

## Production deployment

Production images будуються на VPS із поточного Git checkout. Registry та автоматичний CI/CD pipeline не потрібні.

На сервері створи production `.env.prod` з готового шаблону:

```bash
cp .env.prod.example .env.prod
```

Потім задай власні значення секретів, домену та бази даних. Шаблон містить:

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
DB_PASSWORD=strong-password
DB_ROOT_PASSWORD=another-strong-password
```

`.env.prod.example` не містить реальних секретів і може зберігатися в Git. Файли `.env` і `.env.prod` у Git не додаються.

Makefile використовує `.env` за замовчуванням. Для іншого env-файлу передай його через `ENV_FILE`:

```bash
make ENV_FILE=.env.prod config-prod
make ENV_FILE=.env.prod prod-deploy
```

Deploy:

```bash
git checkout <version>
make ENV_FILE=.env.prod config-prod
make ENV_FILE=.env.prod prod-deploy
```

`make ENV_FILE=.env.prod prod-deploy`:

1. будує PHP, Nginx і MariaDB images;
2. запускає або оновлює production services;
3. виконує `php artisan migrate --force`;
4. кешує config, routes і views;
5. перевіряє `/up` через локальний production port.

Міграції запускаються явно в deploy-команді та не виконуються автоматично при старті контейнера.

### Локальний production-like запуск

Для перевірки production image локально створи окремий env-файл і project name:

```bash
cp .env.prod.example .env.prod
```

У `.env.prod` задай, наприклад:

```dotenv
COMPOSE_PROJECT_NAME=laravel-prod-local
APP_URL=http://localhost:8080
PROD_HTTP_PORT=8080
```

Потім запусти production-оточення:

```bash
make ENV_FILE=.env.prod prod-deploy
make ENV_FILE=.env.prod prod-status
```

Повернення до локальної розробки:

```bash
make ENV_FILE=.env.prod prod-down
make dev-up
```

## Reverse proxy та декілька проєктів на VPS

Production Nginx цього проєкту слухає тільки localhost:

```text
127.0.0.1:8080 → project nginx
```

Зовнішній Nginx на VPS завершує TLS і маршрутизує домен до цього порту:

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

На цьому ж VPS може працювати другий незалежний Laravel-проєкт:

```text
app-one.example.com → 127.0.0.1:8080
app-two.example.com → 127.0.0.1:8081
```

Для другого проєкту потрібні інші значення:

```dotenv
COMPOSE_PROJECT_NAME=second-laravel-app
PROD_HTTP_PORT=8081
```

`COMPOSE_PROJECT_NAME` ізолює контейнери, networks, volumes та image names. Проєкти не повинні використовувати однакові host-порти.

Для другого локального clone застосуй таку саму послідовність першого запуску, але задай у його `.env` окремі `COMPOSE_PROJECT_NAME`, `DEV_HTTP_PORT`, `DB_FORWARD_PORT` і Vite-порти.

## Backup MariaDB

Production MariaDB не має host-порту, тому backup виконується через контейнер:

```bash
docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    exec -T mariadb \
    sh -lc 'mariadb-dump -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' \
    > backup.sql
```

Restore потрібно виконувати обережно, перевіривши цільову базу даних:

```bash
cat backup.sql | docker compose --env-file .env.prod \
    -f docker-compose.yml \
    -f docker-compose.prod.yml \
    exec -T mariadb \
    sh -lc 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"'
```

Backup-файли не варто зберігати в Git або всередині проєкту.

## Структура репозиторію

```text
.
├── app/                    # application code
├── bootstrap/              # Laravel bootstrap
├── config/                 # application configuration
├── database/               # migrations, factories, seeders
├── docker/
│   ├── mariadb/            # MariaDB image and configuration
│   ├── nginx/              # Nginx image and virtual host
│   └── php/                # multi-stage PHP-FPM image
├── docs/                   # detailed architecture and operations docs
├── resources/              # Blade views and frontend source
├── routes/                 # web and console routes
├── docker-compose.yml      # shared Compose configuration
├── docker-compose.dev.yml  # local development overlay
├── docker-compose.prod.yml # production overlay
├── Makefile                # project commands
└── vite.config.js          # Vite and HMR configuration
```

## Документація

- [Docker architecture](docs/architecture.md)
- [Local development](docs/development.md)
- [Production deployment](docs/deployment.md)

## Обережно з volumes

```bash
make tools-clean
```

зупиняє контейнери та видаляє networks, але залишає database volume.

Не використовуй `docker compose down -v`, якщо не хочеш видалити локальні дані MariaDB.
