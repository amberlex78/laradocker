# Laravel Docker Starter

Шаблон Laravel-застосунку, у якому локальна розробка та production deployment
працюють через Docker Compose. Репозиторій можна клонувати як основу нового
проєкту, запустити кілька копій одночасно або розгорнути на Ubuntu VPS.

Стек: Laravel 13, PHP-FPM 8.5, Nginx, MariaDB, Node.js/Vite і Composer.

## Вимоги

- Git;
- Docker Engine із Docker Compose plugin;
- GNU Make.

PHP, Composer, Node.js, Nginx і MariaDB на host встановлювати не потрібно.

## Швидкий старт

Після клонування перейдіть у каталог проєкту та виконайте:

```bash
cp .env.example .env
make install
make up
make migrate
```

Після запуску доступні:

| Сервіс | Адреса |
| --- | --- |
| Laravel | <http://localhost:8000> |
| Health endpoint | <http://localhost:8000/up> |
| Vite HMR | <http://localhost:5173> |
| MariaDB для host-клієнта | `127.0.0.1:3306` |
| Adminer | <http://localhost:8090> |

Compose складається зі спільного файла та окремих development/production
overlay. Контейнери використовують сталі внутрішні порти, а конфлікти між
копіями усуваються зміною host-портів. Детальна схема — у
[Docker-архітектурі](docs/architecture.md).

## Основні команди

```bash
make                 # показати всі доступні targets
make up              # запустити development stack
make down            # зупинити його без видалення даних
make ps              # стан контейнерів
make logs            # логи development stack
make test            # тести
make pint            # форматування PHP
make artisan CMD="about"
make npm CMD="run build"
make prod-ps         # стан production stack
```

## Що ви хочете зробити?

| Завдання | Інструкція |
| --- | --- |
| Запустити один проєкт локально | [Локальна розробка](docs/development.md) |
| Запустити кілька проєктів локально | [Кілька проєктів одночасно](docs/development.md#кілька-проєктів-одночасно) |
| Перевірити production-образ локально | [Production-образ локально](docs/development.md#production-образ-локально) |
| Розгорнути на VPS без домену | [Доступ без домену](docs/deployment.md#доступ-без-домену) |
| Розгорнути з доменом і звичайним HTTPS | [Домен і звичайний HTTPS](docs/deployment.md#домен-і-звичайний-https) |
| Додати Cloudflare | [Необов'язкова інтеграція Cloudflare](docs/cloudflare.md) |
| Оновлювати, діагностувати, робити backup | [Production operations](docs/operations.md) |
| Зрозуміти сервіси, мережі та порти | [Docker-архітектура](docs/architecture.md) |

Звичайний HTTPS через Let's Encrypt є основним production-маршрутом.
Cloudflare описаний окремо як необов'язкова заміна DNS/TLS-рівня.
