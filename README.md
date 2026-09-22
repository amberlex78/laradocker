# Laravel Docker Application

Laravel-застосунок, підготовлений для роботи повністю в Docker.

## Стек

| Компонент | Версія / образ | Призначення |
| --- | --- | --- |
| Laravel | 13.x | Application framework |
| PHP-FPM | `php:8.5-fpm-bookworm` | Виконання PHP-коду |
| Nginx | `nginx:1.30.4-alpine` | Web server і віддача frontend assets |
| MariaDB | `mariadb:11.8.9` | Основна база даних |
| Composer | `composer:2.9.8` | PHP-залежності |
| Node.js | `node:24-bookworm-slim` | Vite та frontend dependencies |

## Швидкий старт

```bash
git clone git@github.com:amberlex78/laradocker.git
```

```bash
cp .env.example .env
make install
make up
make migrate
```

Після запуску:

- Laravel: <http://localhost:8000>;
- health endpoint: <http://localhost:8000/up>;
- Vite HMR: <http://localhost:5173>;
- MariaDB: `127.0.0.1:3306`.

`vendor/`, `node_modules/`, `public/build/`, `public/hot` і Laravel cache створюються від UID/GID поточного користувача хоста.

## Як працює Docker-архітектура

Compose розділений на три файли:

- `docker-compose.yml` — спільні сервіси, мережі, volumes, healthchecks;
- `docker-compose.dev.yml` — локальні bind-mount-и та host-порти;
- `docker-compose.prod.yml` — self-contained production images без bind-mount source code.

Сервіси підключені до ізольованих мереж:

- `frontend` — Nginx ↔ PHP-FPM;
- `backend` — PHP-FPM ↔ MariaDB, мережа `internal`.

### Безпека та збереження даних

- PHP працює від користувача `app`, а не від `root`.
- У development MariaDB доступна локально через `127.0.0.1:3306`.
- У production MariaDB доступна лише контейнерам.
- Дані MariaDB зберігаються у volume `mariadb-data`.

## Як запустити ще один проєкт

Якщо поточний проєкт має залишатися запущеним, у другому `.env` потрібно вказати інші host-порти.

`COMPOSE_PROJECT_NAME` ізолює контейнери, мережі, image names і MariaDB volume проєкту. 

`DB_HOST`, `DB_PORT`, `DB_DATABASE` та внутрішній `VITE_PORT=5173` змінювати не потрібно: вони працюють усередині окремого Compose-оточення.

```dotenv
COMPOSE_PROJECT_NAME=laravel-app-2
APP_NAME=Laravel-2
APP_URL=http://localhost:8001

DEV_HTTP_PORT=8001
VITE_FORWARD_PORT=5174
VITE_PORT=5173
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
```

Для другого clone будуть доступні такі host-порти:

- Laravel: `http://localhost:8001`;
- Vite HMR: `http://localhost:5174`;
- MariaDB: `127.0.0.1:3307`.

## Встановлення проєкту на VPS

Production-інструкція складається з двох частин:

1. Docker збирає PHP, Composer-залежності, frontend і MariaDB та запускає production-контейнери.
2. Системний Nginx на VPS приймає HTTPS-запити від Cloudflare і проксіює їх у Docker на `127.0.0.1:8080`.

Короткий маршрут:

```bash
git clone git@github.com:amberlex78/laradocker.git
cd laradocker
cp .env.prod.example .env.prod
nano .env.prod
make config-prod
make deploy
```

Повна інструкція, включно з перевірками, системним Nginx, Cloudflare Origin Certificate і налаштуванням домену:

- [Розгортання на VPS](docs/deployment.md)

## Основні команди

Подивитися всі доступні команди:

```bash
make
```

### Розробка

```bash
make up           # запустити dev-оточення
make down         # зупинити контейнери без видалення volumes
make ps           # перевірити статус сервісів
make logs         # переглядати логи
```

### Laravel і база даних

```bash
make migrate
make db-seed
make artisan CMD="about"
make artisan CMD="route:list"
```

### Тести та якість коду

```bash
make test
make pint
```

### Composer, npm і shell

```bash
make composer CMD="show"
make npm CMD="run build"
make shell-php
make shell-node
make shell-mariadb
```

### Vite

Vite запускається разом із `make up`. Якщо його потрібно перезапустити:

```bash
make vite
```

## Документація

- [Docker architecture](docs/architecture.md)
- [Local development](docs/development.md)
