# Локальна розробка

Усе середовище працює в Docker. На host не потрібно встановлювати PHP,
Composer, Node.js або MariaDB.

## Вимоги

- Docker Engine із Compose plugin;
- GNU Make;
- Git.

Перевірте інструменти:

```bash
docker --version
docker compose version
make --version
```

## Створення нового проєкту

Клонуйте starter у каталог із назвою нового застосунку:

```bash
git clone https://github.com/amberlex78/laradocker.git my-project
cd my-project
cp .env.example .env
```

Перед першим запуском змініть щонайменше:

```dotenv
COMPOSE_PROJECT_NAME=my-project
APP_NAME=MyProject
APP_URL=http://localhost:8000
DB_DATABASE=my_project
DB_USERNAME=my_project
```

`COMPOSE_PROJECT_NAME` має бути коротким, унікальним і складатися з символів,
придатних для імен Docker-ресурсів. Якщо це вже самостійний репозиторій,
замініть або видаліть початковий Git remote.

## Перший запуск

```bash
make install
make up
make migrate
```

`make install` збирає development images, встановлює Composer і npm
dependencies та створює `APP_KEY`, якщо він порожній. Надалі не замінюйте
наявний ключ без свідомої ротації.

Після запуску:

| Сервіс | Адреса |
| --- | --- |
| Laravel | <http://localhost:8000> |
| Health endpoint | <http://localhost:8000/up> |
| Vite HMR | <http://localhost:5173> |
| MariaDB для host-клієнта | `127.0.0.1:3306` |
| Adminer | <http://localhost:8090> |

## Щоденні команди

Подивитися всі Make targets:

```bash
make
```

Lifecycle і спостереження:

```bash
make up
make down
make ps
make logs
make stats
make vite
```

Laravel і база даних:

```bash
make migrate
make db-seed
make clear
make tinker
make artisan CMD="about"
make artisan CMD="route:list"
```

Тести та форматування:

```bash
make test
make test CMD="tests/Feature/ExampleTest.php"
make pint
```

Dependencies і shell:

```bash
make composer CMD="show"
make npm CMD="run build"
make shell-php
make shell-node
make shell-mariadb
```

`make down` видаляє контейнери та мережі цього Compose project, але не
видаляє `mariadb-data`. Не запускайте `docker compose down -v`, якщо не
плануєте свідомо видалити локальну базу.

## Кілька проєктів одночасно

Контейнери й volumes ізолює `COMPOSE_PROJECT_NAME`. Щоб два development
стеки працювали одночасно, кожен опублікований host-порт також має бути
унікальним.

| Змінна | Project A | Project B |
| --- | --- | --- |
| `COMPOSE_PROJECT_NAME` | `project-a` | `project-b` |
| `DEV_HTTP_PORT` | `8000` | `8001` |
| `VITE_FORWARD_PORT` | `5173` | `5174` |
| `VITE_PORT` | `5173` | `5173` |
| `VITE_HMR_PORT` | `5173` | `5174` |
| `DB_FORWARD_PORT` | `3306` | `3307` |
| `ADMINER_FORWARD_PORT` | `8090` | `8091` |

Мінімальні зміни в `.env` другого проєкту:

```dotenv
COMPOSE_PROJECT_NAME=project-b
APP_NAME=ProjectB
APP_URL=http://localhost:8001
DEV_HTTP_PORT=8001
VITE_FORWARD_PORT=5174
VITE_PORT=5173
VITE_HMR_PORT=5174
DB_FORWARD_PORT=3307
ADMINER_FORWARD_PORT=8091
```

`VITE_PORT=5173`, `DB_HOST=mariadb` і `DB_PORT=3306` залишаються
незмінними: це адреси всередині окремих Docker-мереж. Змінюються лише
host-facing порти.

## Production-образ локально

Production stack не монтує source code, не запускає Vite й не публікує
MariaDB. Для перевірки image використовуйте окремий Compose project:

```bash
cp .env.prod.example .env.prod
```

У `.env.prod` задайте локальні значення:

```dotenv
COMPOSE_PROJECT_NAME=my-project-prod-local
APP_URL=http://localhost:18080
PROD_HTTP_PORT=18080
```

Запуск без Git-політики `make deploy`:

```bash
make config-prod
make prod-build
make prod-up
make prod-migrate
make prod-optimize
```

Перевірте <http://localhost:18080/up>. Зупинити stack:

```bash
make prod-down
```

## Діагностика

Перевіряйте шари послідовно:

```bash
make config-dev
make ps
make logs
curl -I http://127.0.0.1:8000/up
```

Якщо другий проєкт не запускається, перевірте всі п'ять host-портів:
application, Vite, HMR, MariaDB та Adminer. Якщо frontend-зміни не видно,
перезапустіть Vite через `make vite` або перевірте `make logs`.

Деталі мереж і портів описані в [Docker-архітектурі](architecture.md).
Production deployment дивіться в [інструкції для Ubuntu VPS](deployment.md).
